<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $is_admin): Response
    {
        if ($request->user()->is_admin != $is_admin) {
            // toastr('Unable to access that page.', 'error');
            // Flash::success(__('lang.updated_successfully', ['operator' => __('lang.app_setting_global')]));
            return redirect()->route('home');
        }

        return $next($request);
    }
}
