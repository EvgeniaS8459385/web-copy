@php
    /** @var \App\Models\User $student */
    /** @var \App\Models\User $teacher */
    /** @var \App\Models\ChatMessage\ChatMessage[] $chatMessages */

    $currentUserID = $teacher->id;
@endphp
<x-layout>
    <x-slot:title>{{$student->name}}</x-slot:title>

    <h1 class="mb-3">Чат со студентом {{$student->name}}</h1>

    @if(count($chatMessages) === 0)
        <div class="alert alert-secondary" role="alert">
            Чтобы начать общение со студентом, напишите сообщение.
        </div>
    @endif

    @foreach ($chatMessages as $message)
        <div class="d-flex flex-column">
            <div @class([
                "card",
                "mb-3",
                "text-bg-primary" => $message->sender_id === $currentUserID,
                "text-bg-success" => $message->sender_id !== $currentUserID,
                "align-self-end" => $message->sender_id !== $currentUserID,
                "align-self-start" => $message->sender_id === $currentUserID,
            ])>
                <div class="card-header d-flex">
                    <div class="flex-grow-1">
                        {{$message->sender_id === $currentUserID ? 'Вы' : $message->sender->name}}
                    </div>
                    <div style="padding-left: 10px;">
                        {{$message->created_at->format('Y-m-d H:i:s')}}
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        {{$message->message}}
                    </p>
                </div>
            </div>
        </div>
    @endforeach

    <form action="{{route("teacher.chats.send", $student)}}" method="post">
        @csrf
        <div class="mb-3">
            <label for="text" class="form-label">Сообщение</label>
            <textarea class="form-control" id="text" name="message" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</x-layout>
