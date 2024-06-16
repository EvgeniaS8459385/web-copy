<?php

namespace App\View\Components;

use App\Models\ChatMessage\ChatMessage;
use App\Models\InviteRequest\InviteRequest;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Layout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        /** @var User $user */
        $user = Auth::user();

        if (!Auth::check()) {
            return view('components.auth.layout');
        }

        if ($user->isAdmin()) {
            return view('components.admin.layout', ['admin' => $user]);
        }
        if ($user->isTeacher()) {
            return $this->renderTeacherLayout($user);
        }
        if ($user->isSelfStudent()) {
            return $this->renderSelfStudentLayout($user);
        }
        if ($user->isStudent()) {
            return $this->renderStudentLayout($user);
        }

        throw new Exception('User role not found');
    }

    private function renderTeacherLayout(User $user): View|Closure|string
    {
        $chatMessages = ChatMessage::where('receiver_id', $user->id)->get();
        $unreadMessageCount = 0;
        foreach ($chatMessages as $chatMessage) {
            if (!$chatMessage->is_read) {
                $unreadMessageCount++;
            }
        }

        $invitesCount = InviteRequest::where('teacher_id', $user->id)
            ->where('is_accepted', '=', null)
            ->count();

        return view('components.teacher.layout', [
            'teacher' => $user,
            'unreadMessageCount' => $unreadMessageCount,
            'invitesCount' => $invitesCount,
        ]);
    }

    private function renderStudentLayout(User $user): View|Closure|string
    {
        /** @var ChatMessage[] $chatMessages */
        $chatMessages = $user->chatMessages();

        $unreadMessageCount = 0;
        foreach ($chatMessages as $chatMessage) {
            if ($chatMessage->receiver_id === $user->id && !$chatMessage->is_read) {
                $unreadMessageCount++;
            }
        }

        return view('components.student.layout', [
            'student' => $user,
            'chatMessages' => $chatMessages,
            'unreadMessageCount' => $unreadMessageCount,
        ]);
    }

    private function renderSelfStudentLayout(User $user)
    {
        return view('components.selfstudent.layout', [
            'student' => $user,
        ]);
    }
}
