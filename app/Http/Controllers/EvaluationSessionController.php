<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Models\Jawatankuasa;
use App\Services\StosBackendClient;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Live committee evaluation sessions — akuan, per-row reservation, activity log.
 *
 * Thin proxy: it decides whether the signed-in user may act, then forwards to the
 * STOS backend which owns the data. {jenis} selects the committee ('open' =
 * Jawatankuasa Pembuka, 'tech' = Penilaian Teknikal, 'fin' = Penilaian Kewangan),
 * so a second flow reuses this controller without changes.
 *
 * Deliberately separate from JawatankuasaPembukaController so the existing
 * evaluation save/submit logic stays untouched.
 */
class EvaluationSessionController extends Controller
{
    use ResolvesTenderForProcess;

    /** Committee types that can run a live session. Mirrors the STOS service. */
    protected const JENIS = ['open', 'tech', 'fin', 'eval', 'spec'];

    public const PERANAN_PENGERUSI = '1';

    protected const PERANAN_LABELS = [
        '1' => 'Pengerusi',
        '2' => 'Setiausaha',
        '3' => 'Ahli',
    ];

    public function __construct(
        protected StosBackendClient $stos,
    ) {}

    /** Declaration state, committee role, and active locks for the signed-in user. */
    public function session(Request $request, string $jenis): JsonResponse
    {
        return $this->withTender($request, $jenis, function (Tender $tender) use ($jenis) {
            $userId = (int) Auth::id();

            $result = $this->callStos('load evaluation session', ['tender' => $tender->id, 'jenis' => $jenis],
                fn () => $this->stos->getEvaluationSession($tender->id, $jenis, $userId));

            if (! $result['ok']) {
                return response()->json(['message' => $result['message'] ?: 'Gagal memuatkan sesi penilaian.'], $result['status']);
            }

            $data = $result['body']['data'] ?? [];
            // Resolved locally so the page still works if STOS is unreachable.
            $data['peranan'] = $this->resolvePeranan($tender, $jenis, $userId);
            $data['peranan_label'] = self::PERANAN_LABELS[$data['peranan']] ?? null;
            $data['is_admin'] = $this->isAdmin();
            $data['can_submit'] = $this->canSubmit($tender, $jenis);

            return response()->json(['data' => $data]);
        });
    }

    /** Record that this member accepted the Akuan Pengakuan. */
    public function storeDeclaration(Request $request, string $jenis): JsonResponse
    {
        return $this->withTender($request, $jenis, function (Tender $tender) use ($request, $jenis) {
            $userId = (int) Auth::id();

            if (! $this->mayParticipate($tender, $jenis)) {
                return response()->json(['message' => 'Anda bukan ahli jawatankuasa bagi tender ini.'], 403);
            }

            $result = $this->callStos('store declaration', ['tender' => $tender->id, 'jenis' => $jenis],
                fn () => $this->stos->storeEvaluationDeclaration($tender->id, $jenis, [
                    'acting_user_id' => $userId,
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                ]));

            if (! $result['ok']) {
                return response()->json(['message' => $result['message'] ?: 'Gagal merekod akuan.'], $result['status']);
            }

            return response()->json([
                'message' => $result['message'] ?: 'Akuan telah direkodkan.',
                'data' => $result['body']['data'] ?? [],
            ]);
        });
    }

    /** Reserve one vendor row. 409 means another member already holds it. */
    public function acquireLock(Request $request, string $jenis): JsonResponse
    {
        return $this->withTender($request, $jenis, function (Tender $tender) use ($request, $jenis) {
            $request->validate([
                'checklist_item_uuid' => 'required|uuid',
                'vendor_id' => 'required|integer|min:1',
            ]);

            if (! $this->mayParticipate($tender, $jenis)) {
                return response()->json(['message' => 'Anda bukan ahli jawatankuasa bagi tender ini.'], 403);
            }

            $result = $this->callStos('acquire row lock', ['tender' => $tender->id, 'jenis' => $jenis],
                fn () => $this->stos->acquireEvaluationLock($tender->id, $jenis, [
                    'acting_user_id' => (int) Auth::id(),
                    'checklist_item_uuid' => $request->input('checklist_item_uuid'),
                    'vendor_id' => (int) $request->input('vendor_id'),
                    'item_title' => $request->input('item_title'),
                ]));

            if (! $result['ok']) {
                return response()->json([
                    'message' => $result['message'] ?: 'Gagal memulakan penilaian pembekal ini.',
                    'data' => $result['body']['data'] ?? [],
                ], $result['status']);
            }

            return response()->json([
                'message' => $result['message'] ?: 'Anda kini boleh menilai pembekal ini.',
                'data' => $result['body']['data'] ?? [],
            ]);
        });
    }

    /** Release a row this user holds — normally straight after saving its evaluation. */
    public function releaseLock(Request $request, string $jenis): JsonResponse
    {
        return $this->withTender($request, $jenis, function (Tender $tender) use ($request, $jenis) {
            $request->validate([
                'checklist_item_uuid' => 'required|uuid',
                'vendor_id' => 'required|integer|min:1',
            ]);

            $result = $this->callStos('release row lock', ['tender' => $tender->id, 'jenis' => $jenis],
                fn () => $this->stos->releaseEvaluationLock($tender->id, $jenis, [
                    'acting_user_id' => (int) Auth::id(),
                    'checklist_item_uuid' => $request->input('checklist_item_uuid'),
                    'vendor_id' => (int) $request->input('vendor_id'),
                    'item_title' => $request->input('item_title'),
                ]));

            return response()->json(
                ['message' => $result['message'] ?: 'Anda telah selesai menilai pembekal ini.'],
                $result['ok'] ? 200 : $result['status']
            );
        });
    }

    /** Release and log every row saved in one action, in a single call. */
    public function completeRows(Request $request, string $jenis): JsonResponse
    {
        return $this->withTender($request, $jenis, function (Tender $tender) use ($request, $jenis) {
            $request->validate([
                'checklist_item_uuid' => 'required|uuid',
                'rows' => 'required|array|min:1',
                'rows.*.vendor_id' => 'required|integer|min:1',
            ]);

            $result = $this->callStos('complete evaluation rows', ['tender' => $tender->id, 'jenis' => $jenis],
                fn () => $this->stos->completeEvaluationRows($tender->id, $jenis, [
                    'acting_user_id' => (int) Auth::id(),
                    'checklist_item_uuid' => $request->input('checklist_item_uuid'),
                    'rows' => $request->input('rows'),
                    'item_title' => $request->input('item_title'),
                ]));

            return response()->json(['ok' => $result['ok']], $result['ok'] ? 200 : $result['status']);
        });
    }

    /**
     * Poll target while the semak modal is open.
     *
     * Reads the shared database directly — proxying this to STOS would add an HTTP
     * round trip on the hot path. Writes still go through the API.
     */
    public function locks(Request $request, string $jenis): JsonResponse
    {
        return $this->withTender($request, $jenis, function (Tender $tender) use ($request, $jenis) {
            $query = DB::table('tender_evaluation_row_locks as l')
                ->where('l.tender_id', $tender->id)
                ->where('l.jenis_jawatankuasa', $jenis);

            if ($itemUuid = $request->query('checklist_item_uuid')) {
                $query->where('l.checklist_item_uuid', $itemUuid);
            }

            $locks = $query
                ->leftJoin('users as u', 'u.id', '=', 'l.user_id')
                ->get(['l.checklist_item_uuid', 'l.vendor_id', 'l.user_id', 'l.locked_at', 'u.name as user_name'])
                ->map(fn ($row) => [
                    'checklist_item_uuid' => $row->checklist_item_uuid,
                    'vendor_id' => (int) $row->vendor_id,
                    'user_id' => (int) $row->user_id,
                    'user_name' => $row->user_name ?: ('Pengguna #' . $row->user_id),
                    'locked_at' => $row->locked_at ? Carbon::parse($row->locked_at)->toIso8601String() : null,
                ])
                ->values()
                ->all();

            return response()->json(['data' => ['locks' => $locks]]);
        });
    }

    /** Append one activity entry. */
    public function storeLog(Request $request, string $jenis): JsonResponse
    {
        return $this->withTender($request, $jenis, function (Tender $tender) use ($request, $jenis) {
            $request->validate([
                'action' => 'required|string|max:50',
                'checklist_item_uuid' => 'nullable|uuid',
                'vendor_id' => 'nullable|integer|min:1',
                'metadata' => 'nullable|array',
            ]);

            $result = $this->callStos('store activity log', ['tender' => $tender->id, 'jenis' => $jenis],
                fn () => $this->stos->storeEvaluationLog($tender->id, $jenis, [
                    'acting_user_id' => (int) Auth::id(),
                    'action' => $request->input('action'),
                    'checklist_item_uuid' => $request->input('checklist_item_uuid'),
                    'vendor_id' => $request->input('vendor_id'),
                    'metadata' => $request->input('metadata'),
                    'ip_address' => $request->ip(),
                ]));

            // Logging must never block the evaluator; failures are recorded server-side.
            return response()->json(['ok' => $result['ok']]);
        });
    }

    // ─────────────────────────────────────────────────────────────────
    // Role resolution — reusable by any page that needs it
    // ─────────────────────────────────────────────────────────────────

    /** Committee role for a user on this tender, or null when not a member. */
    public function resolvePeranan(Tender $tender, string $jenis, ?int $userId = null): ?string
    {
        $userId = $userId ?: (int) Auth::id();

        if (! $userId) {
            return null;
        }

        $peranan = Jawatankuasa::query()
            ->where('tender_id', $tender->id)
            ->where('jenis_jawatankuasa', $jenis)
            ->where('user_id', $userId)
            ->value('peranan');

        return $peranan !== null ? (string) $peranan : null;
    }

    /** Committee members evaluate; Admin retains full access as before. */
    public function mayParticipate(Tender $tender, string $jenis): bool
    {
        return $this->isAdmin() || $this->resolvePeranan($tender, $jenis) !== null;
    }

    /** Only the Pengerusi finalises the evaluation (Admin keeps its existing override). */
    public function canSubmit(Tender $tender, string $jenis): bool
    {
        return $this->isAdmin() || $this->resolvePeranan($tender, $jenis) === self::PERANAN_PENGERUSI;
    }

    protected function isAdmin(): bool
    {
        $user = Auth::user();

        // Only Admin overrides — spec-management is granted to every committee member.
        return (bool) ($user && $user->hasRole('Admin'));
    }

    // ─────────────────────────────────────────────────────────────────
    // Plumbing
    // ─────────────────────────────────────────────────────────────────

    protected function withTender(Request $request, string $jenis, \Closure $callback): JsonResponse
    {
        if (! in_array($jenis, self::JENIS, true)) {
            return response()->json(['message' => 'Jenis jawatankuasa tidak sah.'], 422);
        }

        $tender = $this->resolveTenderByIdentifier($request->input('tender', $request->query('tender')));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        return $callback($tender);
    }

    /** @return array{ok: bool, body: array, status: int, message: string} */
    protected function callStos(string $action, array $context, \Closure $request): array
    {
        try {
            $response = $request();
            $body = $response->json() ?? [];

            if ($response->successful()) {
                return ['ok' => true, 'body' => $body, 'status' => 200, 'message' => $body['message'] ?? ''];
            }

            Log::error('Evaluation session API error', array_merge($context, [
                'action' => $action,
                'status' => $response->status(),
                'body' => $response->body(),
            ]));

            return [
                'ok' => false,
                'body' => $body,
                // 409 (row already reserved) must reach the browser intact so it can
                // re-render the row as locked rather than showing a generic failure.
                'status' => in_array($response->status(), [403, 404, 409, 422], true) ? $response->status() : 502,
                'message' => $body['message'] ?? '',
            ];
        } catch (\Throwable $e) {
            Log::error("Failed to {$action} via API", array_merge($context, ['error' => $e->getMessage()]));

            return ['ok' => false, 'body' => [], 'status' => 502, 'message' => ''];
        }
    }
}
