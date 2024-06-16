@php
    use App\Models\Module\Module;
    use App\Models\Module\ModulePart;

    /** @var Module $module */
    /** @var ModulePart $modulePart */
@endphp
<x-layout>
    <x-slot:title>{{ $modulePart->name }}</x-slot:title>

    <x-student.modulesidebar :module="$module" :part="$modulePart"/>

    <h1>{{ $modulePart->name }}</h1>

    <p class="lead">
        {!! str_replace('<table', '<table class="table table-bordered"', $modulePart->content) !!}
    </p>

    <form action="{{ route('student.modules.parts.completeTheory', $module) }}" method="post">
        @csrf
        <div class="text-center p-5">
            <button class="btn btn-primary">Далее</button>
        </div>
    </form>
</x-layout>
