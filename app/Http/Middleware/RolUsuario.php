<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()->rol === 1){
           
             //En caso de que no sea el rol 2, redireccional el usuario  hacia home
             return redirect()->route('home');
        }
       
        return $next($request);
    }
}
