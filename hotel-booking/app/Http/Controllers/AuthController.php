<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Показывает форму входа для администратора
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Обрабатывает попытку входа
     */
    public function login(Request $request)
    {
        // Временная простая аутентификация (заменим позже на нормальную)
        if ($request->password === 'admin123') {
            // Сохраняем в сессии что пользователь - администратор
            session(['admin' => true]);
            return redirect()->route('admin.dashboard');
        }

        // Если пароль неверный - возвращаем с ошибкой
        return back()->with('error', 'Неверный пароль');
    }
}