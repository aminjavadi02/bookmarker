<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        if ($token !== 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6ImFtaW4gamF2YWRpIiwiaWF0IjoxOTE2MjM5MDIyfQ.Xlv3girg9OdH97u8hTFXsJg_D5bed4NpW8vSHDNV8zY') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
