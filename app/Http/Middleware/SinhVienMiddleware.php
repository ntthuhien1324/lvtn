<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 17/11/2018
 * Time: 2:54 CH
 */

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class SinhVienMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if(Auth::check()){
            return $next($request);
        } else {
            return redirect('getLogin');
        }
    }
}