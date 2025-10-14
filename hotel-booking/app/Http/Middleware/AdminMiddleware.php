<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, авторизован ли администратор
        if (!session('admin')) {
            // Если нет - перенаправляем на страницу входа
            return redirect()->route('admin.login');
        }

        // Если да - пропускаем запрос дальше
        return $next($request);
    }
}