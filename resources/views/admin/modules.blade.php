<x-layout>
    <x-slot:title>Модули</x-slot:title>

    <div class="d-flex align-items-center">
        <h1 class="flex-fill">Модули</h1>
        <a href="{{ route('admin.modules.create') }}" class="btn text-end btn-primary">
            <i class="bi fa-solid fa-plus"></i>
            Добавить модуль
        </a>
    </div>

    <table class="table table-striped table-hover table-with-actions">
        <thead>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($modules as $module)
            <tr>
                <td>{{ $module->name }}</td>
                <td>{{ $module->description }}</td>
                <td class="actions-cell" style="width: 300px">
                    <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-secondary">
                        <i class="bi fa-solid fa-pen-to-square"></i>
                        Редактировать
                    </a>
                    <form action="{{ route('admin.modules.delete', $module) }}" method="post" class="d-inline">
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
