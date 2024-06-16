@php
/** @var \App\Models\User[] $students */
@endphp
<x-layout>
    <x-slot:title>Студенты</x-slot:title>

    <div class="d-flex align-items-center">
        <h1 class="flex-fill">Студенты</h1>
        <a href="{{ route('admin.students.create') }}" class="btn text-end btn-primary">
            <i class="bi fa-solid fa-plus"></i>
            Добавить студента
        </a>
    </div>

    <table class="table table-striped table-hover table-with-actions">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Группа</th>
                <th>Преподаватель</th>
                <th>Самообучение</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->isSelfStudent() ? '' : $student->studentGroup->name }}</td>
                    <td>{{ $student->isSelfStudent() ? '' : $student->teacher->name }}</td>
                    <td>{{ $student->isSelfStudent() ? 'Да' : 'Нет' }}</td>
                    <td class="actions-cell" style="width: 300px">
                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-secondary">
                            <i class="bi fa-solid fa-pen-to-square"></i>
                            Редактировать
                        </a>
                        <form action="{{ route('admin.students.delete', $student) }}" method="post" class="d-inline">
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
