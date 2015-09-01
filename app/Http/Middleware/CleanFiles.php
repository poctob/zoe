<?php

namespace Zoe\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\TerminableMiddleware;

/**
 * Scrubs the output directory of all files on terminate.
 *
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class CleanFiles implements TerminableMiddleware {

    public function handle($request, Closure $next) {
        return $next($request);
    }

    /**
     * Checks if request if made to the download function, removes the file from
     * disk.
     * @param type $request
     * @param type $response
     */
    public function terminate($request, $response) {
        if ($request->is('download/*') && $request->session()->has('convertedFiles')) {
            $files = $request->session()->get('convertedFiles');

            $fname = '../zoe/storage/exports/' . $files['name'];
            if (file_exists($fname)) {
                unlink($fname);
            }

            $request->session()->forget('convertedFiles');
        }
    }

}
