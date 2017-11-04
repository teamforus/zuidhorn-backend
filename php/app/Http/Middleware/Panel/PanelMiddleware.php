<?php

namespace App\Http\Middleware\Panel;

use Carbon\Carbon;
use Closure;

class PanelMiddleware
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

        if (!$user->hasRole('admin'))
            return response(collect([
                'error'         => 'unauthenticated',
                'description'   => "Unauthenticated."
                ]), 401);

        return $next($request);
    }
}
