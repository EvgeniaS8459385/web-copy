<?php

namespace App\Http\Controllers;

use App\Models\User;

// Это абстрактный класс, который содержит метод user(), который возвращает текущего пользователя.
// Этот метод используется во всех контроллерах, чтобы получить текущего пользователя.
abstract class Controller
{
    protected function user(): User
    {
        /** @var User $user */
        $user = auth()->user();
        return $user;
    }
}
