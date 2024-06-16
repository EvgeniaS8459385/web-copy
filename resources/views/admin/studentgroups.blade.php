<x-layout>
    <x-slot:title>Группы студентов</x-slot:title>

    <div class="d-flex align-items-center">
        <h1 class="flex-fill">Группы студентов</h1>
        <a href="{{ route('admin.studentgroups.create') }}" class="btn text-end btn-primary">
            <i class="bi fa-solid fa-plus"></i>
            Добавить группу
        </a>
    </div>

    <table class="table table-striped table-hover table-with-actions">
        <thead>
        <tr>
            <th>Название</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td class="actions-cell" style="width: 300px">
                    <a href="{{ route('admin.studentgroups.edit', $group) }}" class="btn btn-secondary">
                        <i class="bi fa-solid fa-pen-to-square"></i>
                        Редактировать
                    </a>
                    <form action="{{ route('admin.studentgroups.delete', $group) }}" method="post" class="d-inline">
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
