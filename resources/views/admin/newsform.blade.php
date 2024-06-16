@php
    /** @var \App\Models\News\Article $article */

    $title = $article->title ?? old('title');
    $content = $article->content ?? old('content');
    $breadcrumbs = [
        route('admin.news.show') => 'Новости',
    ];
    if (isset($article)) {
        $breadcrumbs[route('admin.news.edit', $article)] = $article->title;
    } else {
        $breadcrumbs[route('admin.news.create')] = 'Новая новость';
    }
@endphp

<x-layout :breadcrumbs="$breadcrumbs">
    <div class="d-flex align-items-center">
        @isset($article)
            <x-slot:title>{{ $article->name  }}</x-slot:title>
            <h1 class="flex-fill">Редактирование новости</h1>
        @else
            <x-slot:title>Новый модуль</x-slot:title>
            <h1 class="flex-fill">Новая новость</h1>
        @endisset

        <a href="{{ route('admin.news.show') }}" class="btn text-end btn-secondary">
            <i class="bi fa-solid fa-arrow-left"></i>
            Вернуться к списку новостей
        </a>
    </div>

    <div class="container px-0">
        <div class="row gy-5">
            <div class="col-12">
                <form
                    action="{{ isset($article) ? route('admin.news.update', $article) : route('admin.news.store') }}"
                    method="post"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Название</label>
                        <div class="input-group has-validation">
                            <input
                                type="text"
                                name="title"
                                @class([
                                    "form-control",
                                    "is-invalid" => $errors->has("title")
                                ])
                                id="title"
                                value="{{ $title }}"
                            >
                            @if($errors->has("title"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("title") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    @isset($article)
                        <div class="mb-3">
                            <img src="{{route('picture', $article->picture)}}" alt="Изображение" class="img-thumbnail">
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="picture" class="form-label">Изображение</label>
                        <div class="input-group has-validation">
                            <input
                                type="file"
                                name="picture"
                                @class([
                                    "form-control",
                                    "is-invalid" => $errors->has("picture")
                                ])
                                id="picture"
                            >
                            @if($errors->has("picture"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("picture") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Содержание</label>
                        <div class="input-group has-validation">
                            <textarea
                                name="content"
                                @class([
                                    "w-100",
                                    "tinymce-editor",
                                    "form-control",
                                    "is-invalid" => $errors->has("content")
                                ])
                                id="content"
                            >{{$content}}</textarea>
                            @if($errors->has("content"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("content") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
</x-layout>
