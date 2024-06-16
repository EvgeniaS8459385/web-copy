@php
/** @var \App\Models\News\Article[] $news */
@endphp
<x-layout>
    <x-slot:title>Новости</x-slot:title>

    <div class="d-flex align-items-center">
        <h1 class="flex-fill">Новости</h1>
        <a href="{{ route('admin.news.create') }}" class="btn text-end btn-primary">
            <i class="bi fa-solid fa-plus"></i>
            Добавить новость
        </a>
    </div>

    <table class="table table-striped table-hover table-with-actions">
        <thead>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Дата</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($news as $new)
            <tr>
                <td>{{ $new->title }}</td>
                <td>{{ $new->cutContent(100) }}</td>
                <td>{{ $new->created_at }}</td>
                <td class="actions-cell" style="width: 300px">
                    <a href="{{ route('admin.news.edit', $new) }}" class="btn btn-secondary">
                        <i class="bi fa-solid fa-pen-to-square"></i>
                        Редактировать
                    </a>
                    <form action="{{ route('admin.news.delete', $new) }}" method="post" class="d-inline">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi fa-solid fa-trash"></i>
                            Удалить
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-layout>
