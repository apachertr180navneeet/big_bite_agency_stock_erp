<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class PagarBookMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()) {
            $user = Auth::user();
            if($user->role == "pagar_book") {
                return $next($request);
            }else{
                return back()->with("error","Opps! You do not have access this");
            }
        }else{
            return redirect()->route('pagar.book.login');
        }
    }
}
