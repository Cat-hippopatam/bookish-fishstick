<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('AdminMiddleware check', [
            'session_admin' => session('admin'),
            'path' => $request->path()
        ]);
        
        if (!session('admin')) {
            \Log::warning('AdminMiddleware: redirecting to login');
            return redirect()->route('admin.login')->with('error', 'Требуется авторизация');
        }

        return $next($request);
    }
}