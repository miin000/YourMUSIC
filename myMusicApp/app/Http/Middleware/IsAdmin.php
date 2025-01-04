<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu người dùng đã đăng nhập và có quyền admin
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }
        
        // Nếu không phải admin, chuyển hướng về trang chủ hoặc trang khác
        return redirect('/');
    }
}
