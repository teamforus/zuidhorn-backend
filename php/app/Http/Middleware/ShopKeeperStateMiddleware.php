<?php

namespace App\Http\Middleware;

use App\Models\ShopKeeper;
use Closure;

class ShopKeeperStateMiddleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->inExceptArray($request) || !$request->user()) {
            return $next($request);
        }

        $target_user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($target_user->id)->first();

        if ($shop_keeper->state == 'pending') {
            return response(collect([
                'error'         => 'shopkeeper-pending',
                'description'   => "Shopkeeper account is yet to be validated."
                ]), 401);
        }

        if ($shop_keeper->state == 'declined') {
            return response(collect([
                'error'         => 'shopkeeper-declined',
                'description'   => "Shopkeeper account was declined."
                ]), 401);
        }

        return $next($request);
    }
        

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
