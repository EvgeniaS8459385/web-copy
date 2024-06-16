@php

    /** @var \App\Models\User $currentUser */
    $currentUser = auth()->user();

@endphp

<x-layout>
    <x-slot:title>Администраторы</x-slot:title>

    <div class="d-flex align-items-center">
        <h1 class="flex-fill">Администраторы</h1>
        <a href="{{ route('admin.admins.create') }}" class="btn text-end btn-primary">
            <i class="bi fa-solid fa-plus"></i>
            Добавить администратора
        </a>
    </div>

    <table class="table table-striped table-hover table-with-actions">
        <thead>
        <tr>
            <th>Имя</th>
            <th>Email</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($admins as $admin)
            <tr>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td class="actions-cell" style="width: 300px">
                    <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-secondary">
                        <i class="bi fa-solid fa-pen-to-square"></i>
                        Редактировать
                    </a>
                    <form action="{{ route('admin.admins.delete', $admin) }}" method="post" class="d-inline">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger" @disabled($currentUser->id === $admin->id)>
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
