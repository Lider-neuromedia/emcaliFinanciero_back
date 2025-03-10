<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        header("Access-Control-Allow-Origin: * ");
        header('Access-Control-Allow-Credentials: true');  
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 86400');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Application, Authorization, X-Request-With');
        return $next($request);
    }
}
