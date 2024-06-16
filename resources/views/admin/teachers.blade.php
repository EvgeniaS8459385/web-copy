<x-layout>
    <x-slot:title>Преподаватели</x-slot:title>

    <div class="d-flex align-items-center">
        <h1 class="flex-fill">Преподаватели</h1>
        <a href="{{ route('admin.teachers.create') }}" class="btn text-end btn-primary">
            <i class="bi fa-solid fa-plus"></i>
            Добавить преподавателя
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
        @foreach ($teachers as $teacher)
            <tr>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->email }}</td>
                <td class="actions-cell" style="width: 300px">
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-secondary">
                        <i class="bi fa-solid fa-pen-to-square"></i>
                        Редактировать
                    </a>
                    <form action="{{ route('admin.teachers.delete', $teacher) }}" method="post" class="d-inline">
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
