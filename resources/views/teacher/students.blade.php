@php use App\Models\StudentGroup\StudentGroup;use App\Models\User; @endphp
@php
    /**
     * @var StudentGroup $group
     * @var User[] $students
     */
@endphp

<x-layout>
    <x-slot:title>{{$group->name}}}</x-slot:title>

    <h1>Студенты группы {{$group->name}}</h1>

    <table class="table table-striped table-with-actions">
        <thead>
        <tr>
            <th>Имя</th>
            <th>Модулей пройдено</th>
            <th>Процент прохождения</th>
            <th>Результаты тестов</th>
            <th>Задолжник</th>
            <th>Время затраченное на тест</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($students as $student)
            @php
            $pointsAbsolute = $student->pointsAbsolute();
            $completedModuleParts = $student->completedModuleParts()->count()
            @endphp
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{$completedModuleParts}}</td>
                <td>{{$student->completePercent()}}%</td>
                <td>{{$pointsAbsolute}} {{trans_choice('points', $pointsAbsolute)}} из {{$completedModuleParts * 100}}</td>
                <td>{{$student->isDebtor() ? 'Да' : 'Нет'}}</td>
                <td>
                    @php
                        $time = $student->timeSpentOnTests();
                        $hours = floor($time / 3600);
                        $minutes = floor(($time % 3600) / 60);
                        $seconds = $time % 60;
                    @endphp
                    {{ $hours }}:{{$minutes < 10 ? '0' : ''}}{{ $minutes }}:{{$seconds < 10 ? '0' : ''}}{{ $seconds }}
                </td>
                <td class="actions-cell" style="width: 400px">
                    <a href="{{ route('teacher.groups.students.student', [$group, $student]) }}"
                       class="btn btn-primary"
                    >
                        Подробнее
                    </a>
                    <a href="{{ route('teacher.chats.chat', $student) }}"
                       class="btn btn-primary"
                    >
                        <i class="fa-solid fa-comment bi"></i>
                        Чат со студентом
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-layout>
