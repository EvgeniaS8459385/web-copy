@props([
    'sidebar' => '',
])

@php
/** @var \App\Models\User $student */
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
</body>
</html>
