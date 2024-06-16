<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    // Функция для выхода из аккаунта.
    public function logout()
    {
        // Выход из аккаунта.
        Auth::logout();

        // Перенаправление на страницу входа.
        return redirect()->route('login');
    }
}
