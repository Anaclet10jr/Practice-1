<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Usage in routes:
     *   ->middleware('role:admin')
     *   ->middleware('role:agent')
     *   ->middleware('role:admin,agent')   // either role passes
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles, true)) {
            abort(403, 'You do not have access to this area.');
        }

        return $next($request);
    }
}

// ── Separate middleware: block unapproved agents ──────────────────
// Register this as 'agent.approved' in bootstrap/app.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAgentApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isAgent() && ! $user->is_approved) {
            return redirect()->route('agent.pending')
                ->with('warning', 'Your agent account is awaiting admin approval.');
        }

        return $next($request);
    }
}
