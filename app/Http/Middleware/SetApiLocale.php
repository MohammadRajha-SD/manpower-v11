<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class SetApiLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        // $lang = $request->input('lang', 'ar'); 
        
        
          $lang = $request->input('lang') // Check query or body param first
            ?? $request->header('Accept-Language') // Then header
            ?? 'ar'; // Default fallback
        App::setLocale($lang);

        return $next($request);
    }
}
