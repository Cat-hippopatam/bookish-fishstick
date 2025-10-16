<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminService;

class AuthController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($this->adminService->validateLogin($request->email, $request->password)) {
            session(['admin' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Добро пожаловать!');
        }

        return back()->with('error', 'Неверный email или пароль');
    }

    public function logout()
    {
        session()->forget('admin');
        return redirect()->route('admin.login')->with('success', 'Вы вышли из системы');
    }
}