<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class AffiliateRole {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isAdmin = User::isAdmin();
        $isAffiliateUser = User::isAffiliateUser();

        if ($isAdmin || $isAffiliateUser) {
            return $next($request);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response('Unauthorized.', 401);
        } else {
            return redirect('/');
        }
    }
}
