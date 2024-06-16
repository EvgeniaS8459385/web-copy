<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class LoginController extends Controller
{
    // Функция входа в систему.
    // Проверяет введенные данные из формы.
    public function authenticate(Request $request): RedirectResponse
    {
        // Проверка введенных данных.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Проверка на галочку "Запомнить меня".
        $remember = false;
        if ($request->has('remember')) {
            $remember = true;
        }

        // Проверка введенных данных в базе данных.
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Если данные верны, то перенаправляем на главную страницу.
            return redirect()->intended('/');
        }

        // Если данные не верны, то выводим ошибку.
        return back()->withErrors([
            'email' => 'Предоставленные учетные данные не соответствуют нашим записям.',
        ])->onlyInput('email');
    }

    // Функция отображения страницы входа.
    public function login(): View
    {
        // Показывает представление (html) страницы входа.
        return view('auth.login');
    }
}
