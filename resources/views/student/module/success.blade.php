@php
    use App\Models\Module\Module;
    /** @var Module $module */
@endphp
<x-layout>
    <x-slot:title>{{ $module->name }}</x-slot:title>

    <x-student.modulesidebar :module="$module"/>

    <div class="text-center p-5">
        <i class="fa-solid fa-thumbs-up" style="font-size: 100px"></i>

        <h1>Модуль "{{$module->name}}" окончен</h1>

        <p class="lead">
            Поздравляем, вы успешно завершили обучение по модулю "{{$module->name}}".
        </p>

        <a href="{{ route('student.modules.show') }}" class="btn btn-primary">К списку модулей</a>
    </div>
</x-layout>
