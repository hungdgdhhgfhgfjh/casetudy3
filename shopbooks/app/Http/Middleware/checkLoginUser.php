<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkLoginUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if(empty($user))
        {
           
            return redirect()->route('home.index');
        
        } 
        else
        {
    
            if($user->level == "khách hàng")
            {
                return $next($request);
            }   
            else
            {
                return redirect()->route('home.index');
            }
        }
    }
}
