<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Account;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        $account = Account::where('id', Auth::user()->account_id)->firstOrFail();

        if ($account->state === 0) {
            Auth::logout();
        }

        if ($account->accepted_conditions === 0 and $request->getPathInfo() != '/accept-conditions') {
            return redirect()->route('accept_conditions');
        }

        return $next($request);
    }
}
