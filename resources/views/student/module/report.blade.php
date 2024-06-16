@php
    use App\Models\Module\Module;
    use App\Models\Module\ModulePart;

    /** @var Module $module */
    /** @var ModulePart $modulePart */
@endphp
<x-layout>
    <x-slot:title>{{ $modulePart->name }}</x-slot:title>

    <x-student.modulesidebar :module="$module" :part="$modulePart"/>

    <h1>{{ $modulePart->name }}: отчет</h1>

    <p class="lead">
        Пожалуйста загрузите отчет для проверки преподавателем.
    </p>

    <form action="{{ route('student.modules.attachReport', $module) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="report" class="form-label">Отчет</label>
            <input class="form-control" type="file" id="report" name="report">
        </div>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</x-layout>
