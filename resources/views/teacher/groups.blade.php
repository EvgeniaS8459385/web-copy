@php
    /**
     * @var \App\Models\StudentGroup\StudentGroup[] $groups
     */
@endphp

<x-layout>
    <x-slot:title>Группы студентов</x-slot:title>

    <h1>Группы студентов</h1>

    <table class="table table-striped table-with-actions">
        <thead>
        <tr>
            <th>Группа</th>
            <th>Процент прохождения</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($groups as $group)
            <tr>
                <td>{{ $group->name }}</td>
                <td>{{ $group->completePercent() }} %</td>
                <td class="actions-cell" style="width: 400px">
                    <a href="{{ route('teacher.groups.group', $group) }}"
                       class="btn btn-primary"
                    >
                        Список студентов
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-layout>
