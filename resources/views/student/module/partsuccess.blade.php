@php
    use App\Models\Module\Module;
    use App\Models\Module\ModulePart;
    use App\Models\ModuleCompletion\ModulePartCompletion;
    /** @var \App\Models\User $student */
    /** @var Module $module */
    /** @var ModulePart $modulePart */
    /** @var ModulePartCompletion $modulePartCompletion */

    $points = $modulePartCompletion->points();
@endphp
<x-layout>
    <x-slot:title>{{ $modulePart->name }}</x-slot:title>

    <x-student.modulesidebar :module="$module" :part="$modulePart"/>

    <div class="text-center p-5">
        <i class="fa-solid fa-thumbs-up" style="font-size: 100px"></i>

        <h1>Раздел "{{$modulePart->name}}" окончен</h1>

        <p class="lead">
            Поздравляем, вы успешно завершили обучение по разделу "{{$modulePart->name}}".
        </p>

        <form method="post" action="{{ route('student.modules.completeModulePart', $module) }}">
            @csrf
            <button type="submit" class="btn btn-primary">К следующему разделу</button>
        </form>
    </div>

    <div class="text-center p-5">
        <p class="lead">
            Вы набрали {{ $points }} {{ trans_choice('points', $points) }} из 100.

            @if(!$student->isSelfStudent())
                Отчет успешно отправлен преподавателю.
            @endif
        </p>
    </div>
</x-layout>
