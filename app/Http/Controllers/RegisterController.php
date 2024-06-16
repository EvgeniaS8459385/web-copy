<?php

namespace App\Http\Controllers;

use App\Models\StudentGroup\StudentGroup;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    // Функция формы регистрации студента.
    public function registration(): View
    {
        // Получаем всех преподавателей.
        $teachers = User::teachers()->get();

        $studentGroups = StudentGroup::all();

        // Показываем представление (html) формы регистрации.
        return view('auth.register.registration', [
            'teachers' => $teachers,
            'groups' => $studentGroups,
        ]);
    }

    // Функция регистрации студента.
    // Принимает данные из формы регистрации.
    public function register(Request $request)
    {
        // Проверяем, является ли студент на самообучении.
        // Если студент на самообучении, то устанавливаем соответствующий флаг.
        // Если студент не на самообучении, то устанавливаем преподавателя и группу студента.
        $isSelfStudent = $request->get('is_self_student');
        if ($isSelfStudent) {
            // Проверяем валидность данных.
            $values = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string',
                'password_confirmation' => 'required|string',
            ]);
            $values["is_self_student"] = 1;
        } else {
            // Проверяем валидность данных.
            $values = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string',
                'password_confirmation' => 'required|string',
                'teacher_id' => 'required|integer',
                'student_group_id' => 'required|integer',
            ]);
        }

        // Проверяем, что пароли совпадают.
        $password = $request->get('password');
        $password_confirmation = $request->get('password_confirmation');
        if ($password !== $password_confirmation) {
            return back()->with('error', 'Пароли не совпадают');
        }

        try {
            // Создаем нового студента.
            $user = User::create([...$values, 'role' => User::ROLE_STUDENT]);

            // Отправляем событие о регистрации (для отправки письма).
            event(new Registered($user));

            // Авторизуем пользователя.
            Auth::login($user);

            // Перенаправляем на главную страницу.
            return redirect()->route('index');
        } catch (Exception $e) {
            // В случае ошибки, возвращаем обратно с сообщением об ошибке.
            return back()->with('error', $e->getMessage());
        }
    }
}
