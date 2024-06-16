@php
    /**
     * @var \App\Models\ModuleCompletion\ModulePartCompletion[] $modulePartCompletions
     * @var \App\Models\User $student
     * @var \App\Models\StudentGroup\StudentGroup $group
     */
@endphp

<x-layout>
    <x-slot:title>{{ $student->name }}</x-slot:title>

    <h1>Модули студента {{ $student->name }}</h1>

    <table class="table table-striped table-with-actions">
        <thead>
        <tr>
            <th>Модуль</th>
            <th>Оценка</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($modulePartCompletions as $completion)
            <tr>
                <td>{{ $completion->modulePart->name }}</td>
                <td>{{ $completion->points() }}</td>
                <td class="actions-cell" style="width: 550px">
                    <a href="{{ route('teacher.groups.students.report', [
                        $group,
                        $student,
                        $completion,
                    ]) }}"
                       class="btn btn-primary"
                    >
                        <i class="fa-solid fa-eye bi"></i>
                        Посмотреть отчет
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
</x-layout>
