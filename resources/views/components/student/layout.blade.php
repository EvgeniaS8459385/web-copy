@props([
    'sidebar' => '',
])

@php
/** @var \App\Models\User $student */
/** @var \App\Models\ChatMessage\ChatMessage[] $chatMessages */
/** @var int $unreadMessageCount */

$currentUserID = $student->id;
@endphp

<!DOCTYPE html>
<html class="h-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ Vite::asset("resources/css/app.scss")  }}">
</head>
<body class="d-flex flex-column h-100 align-items-stretch flex-nowrap" style="min-width: 800px">
    <header class="p-3 border-bottom bg-body-tertiary navbar">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                    <i class="bi me-2 fa-solid fa-graduation-cap" style="font-size: 32px"></i>
                </a>
                <a class="navbar-brand" href="/">Система обучения</a>
            </div>

            <div class="d-flex">
                <div class="text-end d-flex justify-content-end" style="padding-right:40px">
                    <span>
                        <i class="fa-solid fa-star"></i>
                        {{$student->points()}} {{ trans_choice('points', $student->points()) }}
                    </span>
                </div>
                <div class="dropdown text-end">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu text-small" style="">
                        <li><a class="dropdown-item" href="{{ route('auth.logout') }}">Выйти из системы</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid overflow-auto flex-fill">
        <div class="row h-100">
            @if (!$student->hasVerifiedEmail())
                <div class="col-12 p-3">
                    <div class="alert alert-warning" role="alert">
                        Ваш email не подтвержден. Пожалуйста, подтвердите ваш email.

                    </div>
                    <div>
                        <form action="{{ route('verification.resend') }}" method="post">
                            @csrf
                            <button type="submit"class="btn btn-primary">Отправить новое письмо с подтверждением</button>
                        </form>
                    </div>
                </div>
            @elseif($student->isInviteAccepted())
                @empty($sidebar)
                    <main class="col-12 ms-sm-auto px-md-4 pt-3 overflow-auto h-100">
                        {{ $slot }}
                    </main>
                @else
                    <div class="sidebar col-3 p-3 text-bg-dark overflow-auto h-100">
                        {{ $sidebar }}
                    </div>
                    <main class="col-9 ms-sm-auto px-md-4 pt-3 overflow-auto h-100">
                        {{ $slot }}
                    </main>
                @endempty
            @elseif($student->isInviteDeclined())
                <div class="col-12 p-3">
                    <div class="alert alert-danger" role="alert">
                        Ваша заявка на обучение отклонена преподавателем.
                        Пожалуйста, обратитесь к преподавателю для уточнения причин отклонения заявки.
                    </div>
                </div>
            @else
                <div class="col-12 p-3">
                    <div class="alert alert-warning" role="alert">
                        Ваша заявка на обучение еще не одобрена преподавателем.
                        Пожалуйста, дождитесь одобрения заявки.
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(Session::has('success'))
    <div class="position-absolute bottom-0 d-flex flex-column align-items-center w-100" id="alert-success">
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    </div>
    @endif

    <div class="position-absolute bottom-0 end-0 p-3">
        <a href="#" class="btn btn-dark" id="btn-open-chat" title="Чат с преподавателем">
            <i class="fa-regular fa-message"></i>
            {{$unreadMessageCount > 0 ? $unreadMessageCount : '' }}
        </a>
    </div>

    <div class="position-absolute bottom-0 end-0 p-3" id="chat-window" style="width:600px;display:none;">
        <div class="card text-bg-dark">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <span class="flex-grow-1">Чат с преподавателем</span>
                    <a href="#" id="btn-close-chat" class="btn btn-danger"><i class="fa-solid fa-xmark"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div id="messages-window" style="max-height: 600px; overflow: auto">
                    @if(count($chatMessages) === 0)
                        <div class="alert alert-secondary" role="alert">
                            Чтобы начать общение с преподавателем, напишите сообщение.
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
                </div>

                <form action="{{ route('student.chatMessages.send') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="text" class="form-label">Сообщение</label>
                        <textarea class="form-control" id="text" name="message" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    </div>

    <script type="application/javascript" src="{{ Vite::asset("resources/js/app.js") }}"></script>
    <script>
        function markMessagesAsRead() {
            fetch('{{ route('student.messages.markAsRead') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    _token: '{{ csrf_token() }}',
                }),
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const chat = document.querySelector('#chat-window');
            const closeChatButton = document.querySelector('#btn-close-chat');
            const openChatButton = document.querySelector('#btn-open-chat');
            const messagesWindow = document.querySelector('#messages-window');

            closeChatButton.addEventListener('click', function (e) {
                e.preventDefault();
                chat.style.display = 'none';
            });

            openChatButton.addEventListener('click', function (e) {
                e.preventDefault();
                chat.style.display = 'block';
                messagesWindow.scrollTop = messagesWindow.scrollHeight;
                markMessagesAsRead();
            });

            document.addEventListener('click', function (e) {
                if (e.target.closest('#chat-window') === null && e.target.closest('#btn-open-chat') === null) {
                    chat.style.display = 'none';
                }
            });

            const alertSuccess = document.querySelector('#alert-success');
            if (alertSuccess) {
                setTimeout(() => {
                    alertSuccess.remove();
                }, 5000);
            }
        });
    </script>
</body>
</html>
