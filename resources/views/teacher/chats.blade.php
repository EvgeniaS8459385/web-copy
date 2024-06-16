@php
    use App\Models\ChatMessage\ChatMessage;
    use App\Models\User;

    /** @var User[] $students */
    /** @var User $teacher */
@endphp
<x-layout>
    <x-slot:title>Сообщения</x-slot:title>

    <h1 class="mb-3">Сообщения</h1>

    <table class="table table-with-actions table-striped">
        <tr>
            <th>Студент</th>
            <th>Последнее сообщение</th>
            <th>Дата</th>
            <th></th>
        </tr>
        @foreach ($students as $student)
            @php
                /** @var ChatMessage $lastMessage */
                $lastMessage = $student->chatMessages()->last();
                $hasUnreadMessages = $student->chatMessages()
                    ->where('sender_id', '!=', $teacher->id)
                    ->where('is_read', false)->count() > 0;
                $classes = ['bg-primary' => $hasUnreadMessages, 'text-white' => $hasUnreadMessages];
            @endphp
            <tr>
                <td @class($classes)>{{$student->name}}</td>
                <td @class($classes)>
                    @if ($lastMessage)
                        {{$lastMessage->message}}
                    @endif
                </td>
                <td @class($classes)>
                    @if ($lastMessage)
                        {{$lastMessage->created_at}}
                    @endif
                </td>
                <td @class([...$classes, 'actions-cell']) style="width:200px">
                    <a href="{{route('teacher.chats.chat', $student)}}" class="btn btn-primary">
                        <i class="fa-solid fa-comment bi"></i>
                        Открыть чат
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
</x-layout>
