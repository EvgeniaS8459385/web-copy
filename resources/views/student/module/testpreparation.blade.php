@php
    use App\Models\Module\Module;
    use App\Models\Module\ModulePart;
    /** @var Module $module */
    /** @var ModulePart $modulePart */
@endphp
<x-layout>
    <x-slot:title>{{ $modulePart->name }}</x-slot:title>

    <x-student.modulesidebar :module="$module" :part="$modulePart"/>

    <div class="d-flex align-items-center">
        <h1>{{ $modulePart->name }}: тестирование</h1>
        <div class="flex-grow-1 text-end">
            <i class="fa-solid fa-stopwatch-20"></i>
            Времени на тест: <x-countdown :seconds="$modulePart->time_limit"/>
        </div>
    </div>

    <p class="lead">
        Пожалуйста пройдите тестирование для завершения этапа обучения.
    </p>

    <form action="{{ route('student.modules.parts.startTest', $module) }}" method="post">
        @csrf
        <div class="text-center">
            <button class="btn btn-primary">
                Начать тест
            </button>
        </div>
    </form>
</x-layout>
