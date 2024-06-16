<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage\ChatMessage;
use App\Models\InviteRequest\InviteRequest;
use App\Models\ModuleCompletion\ModulePartCompletion;
use App\Models\StudentGroup\StudentGroup;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TeacherController extends Controller
{
    // Показывает список групп студентов (главная страница преподавателя).
    public function showGroups(): View
    {
        // Получаем все группы студентов.
        $groups = StudentGroup::all();

        // Возвращаем представление (html) списка групп студентов.
        return view('teacher.groups', [
            'groups' => $groups,
        ]);
    }

    // Показывает список студентов в группе.
    // Принимает объект группы студентов.
    public function showGroup(StudentGroup $group): View
    {
        // Возвращаем представление (html) списка студентов в группе.
        return view('teacher.students', [
            'group' => $group,
            // Получаем студентов группы.
            'students' => $group->students,
        ]);
    }

    // Показывает список начатых разделов модулей студента.
    // Принимает объект группы студентов и объект студента.
    public function showStudentModules(StudentGroup $group, User $student): View
    {
        // Получаем все начатые разделы модулей студента.
        $modulePartCompletions = ModulePartCompletion::where('student_id', '=', $student->id)->get();

        // Возвращаем представление (html) списка модулей студента.
        return view('teacher.modules', [
            'modulePartCompletions' => $modulePartCompletions,
            'student' => $student,
            'group' => $group,
        ]);
    }

    // Показывает отчет студента по разделу модуля.
    // Принимает объект группы студентов, объект студента и объект завершения раздела модуля.
    public function showModuleReport(
        StudentGroup         $group,
        User                 $student,
        ModulePartCompletion $modulePartCompletion,
    ): BinaryFileResponse
    {
        // Возвращаем файл отчета студента по разделу модуля.
        return response()->file(storage_path('app/' . $modulePartCompletion->report->file));
    }

    // Показывает список чатов с учениками.
    public function showChats(): View
    {
        // Получаем всех студентов преподавателя.
        $students = User::students()
            ->where('teacher_id', '=', $this->user()->id)->get();

        // Сортируем студентов по времени последнего сообщения.
        $students = $students->sortBy(function (User $student) {
            return $student->chatMessages()->max('created_at');
        });

        // Возвращаем представление (html) списка чатов с учениками.
        return view('teacher.chats', [
            'students' => $students,
            'teacher' => $this->user(),
        ]);
    }

    // Показывает чат со студентом.
    // Принимает объект студента.
    public function showChat(User $student): View
    {
        // Получаем преподавателя.
        $teacher = $this->user();

        // Получаем все сообщения чата между преподавателем и студентом.
        /** @var ChatMessage[] $chatMessages */
        $chatMessages = ChatMessage::where(function ($query) use ($teacher, $student) {
            $query->where('sender_id', $teacher->id)
                ->where('receiver_id', $student->id);
        })->orWhere(function ($query) use ($teacher, $student) {
            $query->where('sender_id', $student->id)
                ->where('receiver_id', $teacher->id);
        })->get();

        // Проходим по всем сообщениям чата.
        foreach ($chatMessages as $chatMessage) {
            // Если получатель сообщения - преподаватель, то помечаем сообщение как прочитанное.
            if ($chatMessage->receiver_id === $teacher->id) {
                $chatMessage->markAsRead();
            }
        }

        // Возвращаем представление (html) чата с учеником.
        return view('teacher.chat', [
            'student' => $student,
            'teacher' => $teacher,
            'chatMessages' => $chatMessages,
        ]);
    }

    // Отправляет сообщение студенту.
    // Принимает объект студента и данные формы.
    public function sendMessage(User $student, Request $request): RedirectResponse
    {
        // Получаем преподавателя.
        $teacher = $this->user();

        // Проверяем данные формы.
        $request->validate([
            'message' => 'required|string',
        ]);

        // Создаем сообщение чата.
        ChatMessage::create([
            'sender_id' => $teacher->id,
            'receiver_id' => $student->id,
            // Текст сообщения из данных формы.
            'message' => $request->input('message'),
        ]);

        // Перенаправляем обратно на страницу чата с учеником.
        return redirect()->back()->with('success', 'Сообщение успешно отправлено');
    }

    // Показывает список заявок на регистрацию студентов.
    public function showInvites(): View
    {
        // Получаем все заявки на регистрацию студентов преподавателя.
        $invites = InviteRequest::where('teacher_id', '=', $this->user()->id)
            ->where('is_accepted', '=', null)
            ->get();

        // Возвращаем представление (html) списка заявок на регистрацию студентов.
        return view('teacher.invites', [
            'invites' => $invites,
        ]);
    }

    // Принимает заявку на регистрацию студента.
    // Принимает объект заявки на регистрацию студента.
    public function acceptInvite(InviteRequest $invite): RedirectResponse
    {
        // Принимаем заявку на регистрацию студента.
        $invite->accept();

        // Перенаправляем обратно на страницу заявок на регистрацию студентов.
        return redirect()->back()->with('success', 'Приглашение принято');
    }

    // Отклоняет заявку на регистрацию студента.
    // Принимает объект заявки на регистрацию студента.
    public function declineInvite(InviteRequest $invite): RedirectResponse
    {
        // Отклоняем заявку на регистрацию студента.
        $invite->decline();

        // Перенаправляем обратно на страницу заявок на регистрацию студентов.
        return redirect()->back()->with('success', 'Приглашение отклонено');
    }
}
