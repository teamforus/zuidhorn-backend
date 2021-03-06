<?php

namespace App\Http\Middleware\Api;

use Carbon\Carbon;
use Closure;

class ShopKeeperApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->token() || !$user->hasRole('shop-keeper'))
            return response(collect([
                'error'         => 'unauthenticated',
                'description'   => "Unauthenticated."
                ]), 401);

        $token = $user->token();

        if ($user->token()->expires_at < Carbon::now()) {
            $request->user()->token()->revoke();
            $request->user()->token()->delete();
        }

        return $next($request);
    }
}
