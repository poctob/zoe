<?php

namespace Zoe\Http\Middleware;

use Closure;

/**
 * Forces redirect to HTTPS
 *
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class SecureRedirect {

    public function handle($request, Closure $next)
    {
            if (!$request->secure() && env('APP_ENV') === 'test') {
                return redirect()->secure('');
            }

            return $next($request); 
    }


}
