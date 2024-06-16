<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;

// Этот контроллер отвечает за перенаправление пользователя на нужную страницу в зависимости от его роли.
class IndexController extends Controller
{
    // Метод index() перенаправляет пользователя на нужную страницу в зависимости от его роли.
    public function index()
    {
        // Если пользователь является администратором, то перенаправляем его на страницу администратора.
        if (Auth::user()->isAdmin()) {
            return redirect(route('admin.modules.show'));
        }

        // Если пользователь является преподавателем, то перенаправляем его на страницу преподавателя.
        if (Auth::user()->isTeacher()) {
            return redirect(route('teacher.groups.show'));
        }

        // Если пользователь является студентом, то перенаправляем его на страницу студента.
        if (Auth::user()->isStudent()) {
            return redirect(route('student.modules.show'));
        }

        // Если пользователь не является ни администратором, ни преподавателем, ни студентом, то выбрасываем исключение (ошибку).
        throw new Exception('Unknown user type');
    }
}
