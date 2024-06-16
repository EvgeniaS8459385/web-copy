@php
    /** @var int $unreadMessageCount */
    /** @var int $invitesCount */

    $routeName = request()->route()->getName();

    $navigation = [
        'teacher.groups.show' => 'Группы',
        'teacher.invites.show' => 'Заявки' . ($invitesCount ? " ({$invitesCount})" : ''),
        'teacher.chats.show' => 'Сообщения' . ($unreadMessageCount ? " ({$unreadMessageCount})" : ''),
    ];
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
    <header class="p-3 mb-3  border-bottom bg-body-tertiary navbar">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                    <i class="bi me-2 fa-solid fa-graduation-cap" style="font-size: 32px"></i>
                </a>
                <a class="navbar-brand" href="/">Панель преподавателя</a>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    @foreach($navigation as $route => $item)
                        <li>
                            <a
                                href="{{ route($route) }}"
                                @class([
                                    'nav-link',
                                    'px-2',
                                    'text-secondary' => $routeName === $route,
                                    'text-black' => $routeName !== $route,
                                ])
                            >
                                {{ $item }}
                            </a>
                        </li>
                    @endforeach
                </ul>
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
    </header>

    <div class="container">
        <div class="row">
            <div class="col-12">
                {{ $slot }}
            </div>
        </div>
    </div>

    @if(Session::has('success'))
        <div class="position-absolute bottom-0 d-flex flex-column align-items-center w-100" id="alert-success">
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        </div>
    @endif

    <script type="application/javascript" src="{{ Vite::asset("resources/js/app.js") }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
