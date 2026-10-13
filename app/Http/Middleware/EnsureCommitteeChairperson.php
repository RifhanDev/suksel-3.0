<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Models\Jawatankuasa;
use App\Tender;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Only the Pengerusi may finalise a committee evaluation; other members evaluate
 * but cannot submit. Applied at the route so the existing controller action stays
 * untouched, and so the rule cannot be bypassed by driving the endpoint directly.
 *
 * Usage: ->middleware('committee.pengerusi:open')
 * where the parameter is the jenis_jawatankuasa ('open', 'tech', 'fin', ...).
 * An optional second parameter names the action for the denial message, e.g.
 * ->middleware('committee.pengerusi:tech,mengemaskini draf laporan ini') — defaults
 * to "menghantar keputusan penilaian ini" for the finalize/confirm routes.
 */
class EnsureCommitteeChairperson
{
    use ResolvesTenderForProcess;

    private const PERANAN_PENGERUSI = '1';

    public function handle(Request $request, Closure $next, string $jenis, string $action = 'menghantar keputusan penilaian ini')
    {
        $user = Auth::user();

        if (! $user) {
            return $this->deny($request, 'Sila log masuk semula.', 401);
        }

        // Only Admin overrides — specification-management is not a finalize bypass.
        if ($user->hasRole('Admin')) {
            return $next($request);
        }

        $tender = $this->resolveTenderFromRequest($request);

        if (! $tender) {
            return $this->deny($request, 'Tender tidak ditemui.', 404);
        }

        $peranan = Jawatankuasa::query()
            ->where('tender_id', $tender->id)
            ->where('jenis_jawatankuasa', $jenis)
            ->where('user_id', $user->id)
            ->value('peranan');

        if ((string) $peranan !== self::PERANAN_PENGERUSI) {
            return $this->deny(
                $request,
                "Hanya Pengerusi Jawatankuasa boleh {$action}.",
                403
            );
        }

        return $next($request);
    }

    /** Tender may come from the body, the query, or a {tender} route parameter. */
    protected function resolveTenderFromRequest(Request $request): ?Tender
    {
        $routeTender = $request->route('tender');

        if ($routeTender instanceof Tender) {
            return $routeTender;
        }

        return $this->resolveTenderByIdentifier(
            $request->input('tender', $request->query('tender')) ?? $routeTender
        );
    }

    protected function deny(Request $request, string $message, int $status)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], $status);
        }

        abort($status, $message);
    }
}
