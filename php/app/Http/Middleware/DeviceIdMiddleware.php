<?php

namespace App\Http\Middleware;

use \App\Models\ShopKeeper;
use Closure;

class DeviceIdMiddleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/api/shop-keeper/sign-up'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        $target_user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($target_user->id)->first();

        if (!$target_user->hasRole('shop-keeper') || !$shop_keeper)
            abort(401);

        $device_id = $request->header('Device-Id');

        if (!$device_id) {
            return response(collect(['error' => 'no-device-id']), 401);
        }
        
        $device_status = $shop_keeper->checkDevice($device_id);

        if (!$device_status) {
            $shop_keeper->requestDeviceApprovement($device_id);
            return response(collect(['error' => 'device-unknown']), 401);
        } elseif ($device_status->status == 'pending') {
            return response(collect(['error' => 'device-pending']), 401);
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
