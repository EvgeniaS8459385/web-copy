<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    // Функция для отображения формы запроса сброса пароля.
    public function create(): View
    {
        // Возвращаем представление с формой запроса сброса пароля.
        return view('auth.forgotpassword.request');
    }

    // Функция для отправки письма с ссылкой на сброс пароля.
    public function store(Request $request)
    {
        // Проверяем валидность введенного email.
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Отправляем письмо с ссылкой на сброс пароля.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Возвращаем пользователя на страницу входа с сообщением о статусе отправки письма.
        return $status == Password::RESET_LINK_SENT
            ? redirect()->route('login')->with('status', __($status))
            : redirect()->route('login')->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
