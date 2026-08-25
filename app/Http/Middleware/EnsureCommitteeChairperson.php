<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Models\Jawatankuasa;
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
 */
class EnsureCommitteeChairperson
{
    use ResolvesTenderForProcess;

    private const PERANAN_PENGERUSI = '1';

    public function handle(Request $request, Closure $next, string $jenis)
    {
        $user = Auth::user();

        if (! $user) {
            return $this->deny($request, 'Sila log masuk semula.', 401);
        }

        // Admin keeps the oversight access it has always had.
        if ($user->hasRole('Admin') || $user->can('tender:specification-management')) {
            return $next($request);
        }

        $tender = $this->resolveTenderByIdentifier($request->input('tender', $request->query('tender')));

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
                'Hanya Pengerusi Jawatankuasa boleh menghantar keputusan penilaian ini.',
                403
            );
        }

        return $next($request);
    }

    protected function deny(Request $request, string $message, int $status)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], $status);
        }

        abort($status, $message);
    }
}
