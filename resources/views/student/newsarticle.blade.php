@php
/** @var \App\Models\News\Article $article */
@endphp
<x-layout>
    <x-slot:title>Модули</x-slot:title>

    <h1 class="mb-3">{{$article->title}}</h1>

    <div class="mb-3">
        <img src="{{route('picture', $article->picture)}}" class="img-fluid" alt="picture" />
    </div>

    <div>
        {!! $article->content !!}
    </div>
</x-layout>
