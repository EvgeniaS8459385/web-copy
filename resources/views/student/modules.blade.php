@php
/** @var \App\Models\News\Article[] $articles */
@endphp
<x-layout>
    <x-slot:title>Модули</x-slot:title>

    <x-slot:sidebar>
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4">Модули</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            @foreach ($modules as $module)
                <li class="nav-item">
                    <a href="{{ route('student.modules.module', $module) }}" class="nav-link text-white"
                       aria-current="page">
                        {{ $module->name }}
                    </a>
                </li>
            @endforeach
        </ul>
        <hr>
    </x-slot:sidebar>

    <div class="text-center p-5">
        <i class="fa-solid fa-graduation-cap" style="font-size: 200px"></i>

        <h1>Добро пожаловать в систему обучения</h1>

        <p class="lead">
            Пожалуйста выберете модуль для обучения из списка слева.
        </p>
    </div>

    <div class="text-center p-5 container">
        <div class="row">
            @foreach ($articles as $i => $article)
                <div class="col-4">
                    <div class="card">
                        <a href="{{route('student.news.article', $article)}}">
                            <img src="{{route('picture', $article->picture)}}" class="card-img-top" alt="picture" />
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{route('student.news.article', $article)}}" class="text-dark">
                                    {{ $article->title }}
                                </a>
                            </h5>
                            <p class="card-text">{{ $article->cutContent(100) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layout>
