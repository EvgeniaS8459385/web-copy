@php
    /** @var \App\Models\Module $module */
@endphp
<x-layout>
    <x-slot:title>{{ $module->name }}</x-slot:title>

    <x-student.modulesidebar :module="$module"/>

    <h1>{{ $module->name }}</h1>

    <p class="lead">
        {{ $module->description }}
    </p>

    <div class="text-center">
        <form
            method="post"
            action="{{ route('student.modules.start', $module) }}"
        >
            @csrf
            <button class="btn btn-primary" type="submit">Начать обучение</button>
        </form>
    </div>
</x-layout>
