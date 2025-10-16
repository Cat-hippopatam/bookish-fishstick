<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin')) {
            return redirect()->route('admin.login')->with('error', 'Требуется авторизация');
        }

        return $next($request);
    }
}