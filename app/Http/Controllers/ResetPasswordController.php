<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\Rules;


class ResetPasswordController extends Controller
{
    // Функция для сброса пароля.
    public function create(): View
    {
        // Возвращаем представление (html) для формы сброса пароля.
        return view('auth.resetpassword.reset', [
            'request' => request()
        ]);
    }

    // Функция для отправки запроса на сброс пароля.
    // Принимает данные из формы.
    public function store(Request $request)
    {
        // Проверяем валидность данных.
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
