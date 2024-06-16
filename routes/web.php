<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsStudent;
use App\Http\Middleware\EnsureUserIsTeacher;
use Illuminate\Support\Facades\Route;

// Обработчик маршрута для отображения изображений.
Route::get('/picture/{picture}', function ($picture) {
    return response()->file(storage_path('app/' . $picture));
})->name('picture')->where('picture', '.*');

// Группа маршрутов для любых авторизованных пользователей.
// Перед выполнением маршрутов проверяется, что пользователь авторизован (auth).
Route::middleware('auth')->group(function() {
    // Маршрут для главной страницы (для каждого типа пользователя своя).
    Route::get('/', [IndexController::class, 'index'])->name('index');

    // Маршрут для выхода из учетной записи.
    Route::get('/auth/logout', [LogoutController::class, 'logout'])->name('auth.logout');

    // Маршруты для подтверждения электронной почты.
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->name('verification.resend');
});

// Группа маршрутов для студента.
// Перед выполнением маршрутов проверяется, что пользователь авторизован (auth) и является студентом (EnsureUserIsStudent).
// Для всех маршрутов в этой группе добавляется префикс /student.
Route::middleware(['auth', EnsureUserIsStudent::class])->prefix('/student')->group(function () {
    // Маршрут для перенаправления на страницу с модулями (главная страница студента).
    Route::get('/', function () {
        return redirect()->route('student.modules.show');
    });

    // Маршрут для отображения списка модулей.
    Route::get('/modules', [StudentController::class, 'showModules'])->name('student.modules.show');

    // Маршрут для отображения прохождения модуля (теория, тесты, отчеты).
    Route::get('/modules/{module}', [StudentController::class, 'showModule'])->name('student.modules.module');

    // Маршрут для старта модуля.
    Route::post('/modules/{module}/start', [StudentController::class, 'startModule'])->name('student.modules.start');

    // Маршрут для завершения теории раздела модуля.
    Route::post('/modules/{module}/completeTheory', [StudentController::class, 'completeTheory'])->name('student.modules.parts.completeTheory');

    // Маршрут для старта теста раздела модуля.
    Route::post('/modules/{module}/startTest', [StudentController::class, 'startTest'])->name('student.modules.parts.startTest');

    // Маршрут для завершения теста раздела модуля.
    Route::post('/modules/{module}/endTest', [StudentController::class, 'endTest'])->name('student.modules.parts.endTest');

    // Маршрут для прикрепления отчета к разделу модуля.
    Route::post('/modules/{module}/attachReport', [StudentController::class, 'attachReport'])->name('student.modules.attachReport');

    // Маршрут для завершения раздела модуля.
    Route::post('/modules/{module}/complete', [StudentController::class, 'completeModulePart'])->name('student.modules.completeModulePart');

    // Маршрут для отправки сообщения в чат.
    Route::post('/chatMessages', [StudentController::class, 'sendMessage'])->name('student.chatMessages.send');

    // Маршрут для пометки сообщений в чате как прочитанных.
    Route::post('/chatMessages/markMessagesAsRead', [StudentController::class, 'markMessagesAsRead'])->name('student.messages.markAsRead');

    // Маршрут для отображения новости.
    Route::get('/news/{article}', [StudentController::class, 'showArticle'])->name('student.news.article');
});

// Группа маршрутов для преподавателя.
// Перед выполнением маршрутов проверяется, что пользователь авторизован (auth) и является преподавателем (EnsureUserIsTeacher).
// Для всех маршрутов в этой группе добавляется префикс /teacher.
Route::middleware(['auth', EnsureUserIsTeacher::class])->prefix('/teacher')->group(function() {
    // Маршрут для перенаправления на страницу с группами студентов (главная страница преподавателя).
    Route::get('/', function () {
        return redirect()->route('teacher.groups.show');
    });

    // Маршрут для отображения списка групп студентов.
    Route::get('/groups', [TeacherController::class, 'showGroups'])->name('teacher.groups.show');

    // Маршрут для отображения списка студентов группы.
    Route::get('/groups/{group}', [TeacherController::class, 'showGroup'])->name('teacher.groups.group');

    // Маршрут для отображения списка модулей студента.
    Route::get('/groups/{group}/students/{student}', [TeacherController::class, 'showStudentModules'])->name('teacher.groups.students.student');

    // Маршрут для получения отчета по модулю студента.
    Route::get('/groups/{group}/students/{student}/modules/{modulePartCompletion}/report', [TeacherController::class, 'showModuleReport'])->name('teacher.groups.students.report');

    // Маршрут для отображения заявок студентов на запись к преподавателю.
    Route::get('/invites', [TeacherController::class, 'showInvites'])->name('teacher.invites.show');

    // Маршрут для принятия заявки студента на запись к преподавателю.
    Route::get('/invites/{invite}/accept', [TeacherController::class, 'acceptInvite'])->name('teacher.invites.accept');

    // Маршрут для отклонения заявки студента на запись к преподавателю.
    Route::get('/invites/{invite}/decline', [TeacherController::class, 'declineInvite'])->name('teacher.invites.decline');

    // Маршрут для отображения чатов с учениками.
    Route::get('/chats', [TeacherController::class, 'showChats'])->name('teacher.chats.show');

    // Маршрут для отображения чата с учеником.
    Route::get('/chats/{student}', [TeacherController::class, 'showChat'])->name('teacher.chats.chat');

    // Маршрут для отправки сообщения в чат.
    Route::post('/chats/{student}', [TeacherController::class, 'sendMessage'])->name('teacher.chats.send');
});

// Группа маршрутов для администратора.
// Перед выполнением маршрутов проверяется, что пользователь авторизован (auth) и является администратором (EnsureUserIsAdmin).
// Для всех маршрутов в этой группе добавляется префикс /admin.
Route::middleware(['auth', EnsureUserIsAdmin::class])->prefix('/admin')->group(function() {
    // Маршрут для перенаправления на страницу с модулями (главная страница администратора).
    Route::get('/', function () {
        return redirect()->route('admin.modules.show');
    });

    // Маршрут для отображения списка модулей.
    Route::get('/modules', [AdminController::class, 'showModules'])->name('admin.modules.show');

    // Маршрут для отображения формы создания модуля.
    Route::get('/modules/new', [AdminController::class, 'createModule'])->name('admin.modules.create');

    // Маршрут для создания модуля в базе данных (принимает данные из формы).
    Route::post('/modules/new', [AdminController::class, 'storeModule'])->name('admin.modules.store');

    // Маршрут для отображения формы редактирования модуля.
    Route::get('/modules/{module}', [AdminController::class, 'editModule'])->name('admin.modules.edit');

    // Маршрут для обновления модуля в базе данных (принимает данные из формы).
    Route::post('/modules/{module}', [AdminController::class, 'updateModule'])->name('admin.modules.update');

    // Маршрут для удаления модуля из базы данных.
    Route::delete('/modules/{module}', [AdminController::class, 'deleteModule'])->name('admin.modules.delete');

    // Маршрут для отображения списка разделов модуля.
    Route::get('/modules/{module}/parts/new', [AdminController::class, 'createModulePart'])->name('admin.modules.parts.create');

    // Маршрут для создания раздела модуля в базе данных (принимает данные из формы).
    Route::post('/modules/{module}/parts/new', [AdminController::class, 'storeModulePart'])->name('admin.modules.parts.store');

    // Маршрут для отображения формы редактирования раздела модуля.
    Route::get('/modules/{module}/parts/{part}', [AdminController::class, 'editModulePart'])->name('admin.modules.parts.edit');

    // Маршрут для обновления раздела модуля в базе данных (принимает данные из формы).
    Route::post('/modules/{module}/parts/{part}', [AdminController::class, 'updateModulePart'])->name('admin.modules.parts.update');

    // Маршрут для удаления раздела модуля из базы данных.
    Route::delete('/modules/{module}/parts/{part}', [AdminController::class, 'deleteModulePart'])->name('admin.modules.parts.delete');

    // Маршрут для изменения порядка разделов модуля (перемещение вперед).
    Route::post('/modules/{module}/parts/{part}/raiseOrder', [AdminController::class, 'raiseOrderModulePart'])->name('admin.modules.parts.raiseOrder');

    // Маршрут для изменения порядка разделов модуля (перемещение назад).
    Route::post('/modules/{module}/parts/{part}/reduceOrder', [AdminController::class, 'reduceOrderModulePart'])->name('admin.modules.parts.reduceOrder');

    // Маршрут для отображения формы создания вопроса раздела модуля.
    Route::get('/modules/{module}/parts/{part}/questions/new', [AdminController::class, 'createQuestion'])->name('admin.modules.parts.questions.create');

    // Маршрут для создания вопроса раздела модуля в базе данных (принимает данные из формы).
    Route::post('/modules/{module}/parts/{part}/questions/new', [AdminController::class, 'storeQuestion'])->name('admin.modules.parts.questions.store');

    // Маршрут для отображения формы редактирования вопроса раздела модуля.
    Route::get('/modules/{module}/parts/{part}/questions/{question}', [AdminController::class, 'editQuestion'])->name('admin.modules.parts.questions.edit');

    // Маршрут для обновления вопроса раздела модуля в базе данных (принимает данные из формы).
    Route::post('/modules/{module}/parts/{part}/questions/{question}', [AdminController::class, 'updateQuestion'])->name('admin.modules.parts.questions.update');

    // Маршрут для удаления вопроса раздела модуля из базы данных.
    Route::delete('/modules/{module}/parts/{part}/questions/{question}', [AdminController::class, 'deleteQuestion'])->name('admin.modules.parts.questions.delete');

    // Маршрут для изменения порядка вопросов раздела модуля (перемещение вперед).
    Route::post('/modules/{module}/parts/{part}/questions/{question}/raiseOrder', [AdminController::class, 'raiseOrderQuestion'])->name('admin.modules.parts.questions.raiseOrder');

    // Маршрут для изменения порядка вопросов раздела модуля (перемещение назад).
    Route::post('/modules/{module}/parts/{part}/questions/{question}/reduceOrder', [AdminController::class, 'reduceOrderQuestion'])->name('admin.modules.parts.questions.reduceOrder');

    // Маршрут для отображения формы создания ответа на вопрос раздела модуля.
    Route::get('/modules/{module}/parts/{part}/questions/{question}/answers/new', [AdminController::class, 'createAnswer'])->name('admin.modules.parts.questions.answers.create');

    // Маршрут для создания ответа на вопрос раздела модуля в базе данных (принимает данные из формы).
    Route::post('/modules/{module}/parts/{part}/questions/{question}/answers/new', [AdminController::class, 'storeAnswer'])->name('admin.modules.parts.questions.answers.store');

    // Маршрут для отображения формы редактирования ответа на вопрос раздела модуля.
    Route::get('/modules/{module}/parts/{part}/questions/{question}/answers/{answer}', [AdminController::class, 'editAnswer'])->name('admin.modules.parts.questions.answers.edit');

    // Маршрут для обновления ответа на вопрос раздела модуля в базе данных (принимает данные из формы).
    Route::post('/modules/{module}/parts/{part}/questions/{question}/answers/{answer}', [AdminController::class, 'updateAnswer'])->name('admin.modules.parts.questions.answers.update');

    // Маршрут для удаления ответа на вопрос раздела модуля из базы данных.
    Route::delete('/modules/{module}/parts/{part}/questions/{question}/answers/{answer}', [AdminController::class, 'deleteAnswer'])->name('admin.modules.parts.questions.answers.delete');

    // Маршрут для изменения порядка ответов на вопрос раздела модуля (перемещение вперед).
    Route::post('/modules/{module}/parts/{part}/questions/{question}/answers/{answer}/raiseOrder', [AdminController::class, 'raiseOrderAnswer'])->name('admin.modules.parts.questions.answers.raiseOrder');

    // Маршрут для изменения порядка ответов на вопрос раздела модуля (перемещение назад).
    Route::post('/modules/{module}/parts/{part}/questions/{question}/answers/{answer}/reduceOrder', [AdminController::class, 'reduceOrderAnswer'])->name('admin.modules.parts.questions.answers.reduceOrder');

    // Маршрут для установки правильного ответа на вопрос раздела модуля.
    Route::post('/modules/{module}/parts/{part}/questions/{question}/answers/{answer}/setIsCorrect', [AdminController::class, 'setIsCorrectAnswer'])->name('admin.modules.parts.questions.answers.setIsCorrect');

    // Маршрут для отображения списка студентов.
    Route::get('/students', [AdminController::class, 'showStudents'])->name('admin.students.show');

    // Маршрут для отображения формы создания студента.
    Route::get('/students/new', [AdminController::class, 'createStudent'])->name('admin.students.create');

    // Маршрут для создания студента в базе данных (принимает данные из формы).
    Route::post('/students/new', [AdminController::class, 'storeStudent'])->name('admin.students.store');

    // Маршрут для отображения формы редактирования студента.
    Route::get('/students/{student}', [AdminController::class, 'editStudent'])->name('admin.students.edit');

    // Маршрут для обновления студента в базе данных (принимает данные из формы).
    Route::post('/students/{student}', [AdminController::class, 'updateStudent'])->name('admin.students.update');

    // Маршрут для удаления студента из базы данных.
    Route::delete('/students/{student}', [AdminController::class, 'deleteStudent'])->name('admin.students.delete');

    // Маршрут для отображения списка преподавателей.
    Route::get('/teachers', [AdminController::class, 'showTeachers'])->name('admin.teachers.show');

    // Маршрут для отображения формы создания преподавателя.
    Route::get('/teachers/new', [AdminController::class, 'createTeacher'])->name('admin.teachers.create');

    // Маршрут для создания преподавателя в базе данных (принимает данные из формы).
    Route::post('/teachers/new', [AdminController::class, 'storeTeacher'])->name('admin.teachers.store');

    // Маршрут для отображения формы редактирования преподавателя.
    Route::get('/teachers/{teacher}', [AdminController::class, 'editTeacher'])->name('admin.teachers.edit');

    // Маршрут для обновления преподавателя в базе данных (принимает данные из формы).
    Route::post('/teachers/{teacher}', [AdminController::class, 'updateTeacher'])->name('admin.teachers.update');

    // Маршрут для удаления преподавателя из базы данных.
    Route::delete('/teachers/{teacher}', [AdminController::class, 'deleteTeacher'])->name('admin.teachers.delete');

    // Маршрут для отображения списка групп студентов.
    Route::get('/studentgroups', [AdminController::class, 'showStudentGroups'])->name('admin.studentgroups.show');

    // Маршрут для отображения формы создания группы студентов.
    Route::get('/studentgroups/new', [AdminController::class, 'createStudentGroup'])->name('admin.studentgroups.create');

    // Маршрут для создания группы студентов в базе данных (принимает данные из формы).
    Route::post('/studentgroups/new', [AdminController::class, 'storeStudentGroup'])->name('admin.studentgroups.store');

    // Маршрут для отображения формы редактирования группы студентов.
    Route::get('/studentgroups/{group}', [AdminController::class, 'editStudentGroup'])->name('admin.studentgroups.edit');

    // Маршрут для обновления группы студентов в базе данных (принимает данные из формы).
    Route::post('/studentgroups/{group}', [AdminController::class, 'updateStudentGroup'])->name('admin.studentgroups.update');

    // Маршрут для удаления группы студентов из базы данных.
    Route::delete('/studentgroups/{group}', [AdminController::class, 'deleteStudentGroup'])->name('admin.studentgroups.delete');

    // Маршрут для отображения списка новостей.
    Route::get('/admins', [AdminController::class, 'showAdmins'])->name('admin.admins.show');

    // Маршрут для отображения формы создания администратора.
    Route::get('/admins/new', [AdminController::class, 'createAdmin'])->name('admin.admins.create');

    // Маршрут для создания администратора в базе данных (принимает данные из формы).
    Route::post('/admins/new', [AdminController::class, 'storeAdmin'])->name('admin.admins.store');

    // Маршрут для отображения формы редактирования администратора.
    Route::get('/admins/{admin}', [AdminController::class, 'editAdmin'])->name('admin.admins.edit');

    // Маршрут для обновления администратора в базе данных (принимает данные из формы).
    Route::post('/admins/{admin}', [AdminController::class, 'updateAdmin'])->name('admin.admins.update');

    // Маршрут для удаления администратора из базы данных.
    Route::delete('/admins/{admin}', [AdminController::class, 'deleteAdmin'])->name('admin.admins.delete');

    // Маршрут для отображения списка новостей.
    Route::get('/news', [AdminController::class, 'showArticles'])->name('admin.news.show');

    // Маршрут для отображения формы создания новости.
    Route::get('/news/new', [AdminController::class, 'createArticle'])->name('admin.news.create');

    // Маршрут для создания новости в базе данных (принимает данные из формы).
    Route::post('/news/new', [AdminController::class, 'storeArticle'])->name('admin.news.store');

    // Маршрут для отображения формы редактирования новости.
    Route::get('/news/{article}', [AdminController::class, 'editArticle'])->name('admin.news.edit');

    // Маршрут для обновления новости в базе данных (принимает данные из формы).
    Route::post('/news/{article}', [AdminController::class, 'updateArticle'])->name('admin.news.update');

    // Маршрут для удаления новости из базы данных.
    Route::delete('/news/{article}', [AdminController::class, 'deleteArticle'])->name('admin.news.delete');
});

// Группа маршрутов для гостя (неавторизованного пользователя).
// Для всех маршрутов в этой группе добавляется префикс /auth.
Route::middleware('guest')->prefix('/auth')->group(function() {
    // Маршрут для отображения страницы входа.
    Route::get('/login', [LoginController::class, 'login'])->name('login');

    // Маршрут для аутентификации пользователя (принимает данные из формы).
    Route::post('/login', [LoginController::class, 'authenticate']);

    // Маршрут для отображения страницы регистрации.
    Route::get('/register', [RegisterController::class, 'registration'])->name('registration');

    // Маршрут для регистрации пользователя (принимает данные из формы).
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    // Маршрут для отображения страницы восстановления пароля.
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');

    // Маршрут для отправки письма с ссылкой на восстановление пароля (принимает данные из формы).
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

    // Маршрут для отображения страницы ввода нового пароля.
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');

    // Маршрут для сброса пароля (принимает данные из формы).
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.store');
});
