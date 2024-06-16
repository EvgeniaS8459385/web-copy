@php
    /** @var \App\Models\User $student */

    $name = $student->name ?? old('name');
    $email = $student->email ?? old('email');

    $breadcrumbs = [
        route('admin.students.show') => 'Студенты',
    ];
    if (isset($student)) {
        $breadcrumbs[route('admin.students.edit', $student)] = $student->name;
    } else {
        $breadcrumbs[route('admin.students.create')] = 'Новый студент';
    }
@endphp

<x-layout>
    @isset($student)
        <x-slot:title>{{ $student->name  }}</x-slot:title>
        <h1>Редактирование студента</h1>
    @else
        <x-slot:title>Новый студент</x-slot:title>
        <h1>Новый студент</h1>
    @endisset

    <div class="container px-0">
        <div class="row gy-5">
            <div class="col-12">
                <form
                    action="{{ isset($student) ? route('admin.students.update', $student) : route('admin.students.store') }}"
                    method="post"
                >
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <div class="input-group has-validation">
                            <input
                                type="text"
                                name="name"
                                @class([
                                    "form-control",
                                    "is-invalid" => $errors->has("name")
                                ])
                                id="name"
                                value="{{ $name }}"
                            >
                            @if($errors->has("name"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("name") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Email</label>
                        <div class="input-group has-validation">
                            <input
                                type="email"
                                name="email"
                                @class([
                                    "form-control",
                                    "is-invalid" => $errors->has("email")
                                ])
                                id="email"
                                value="{{ $email }}"
                            >
                            @if($errors->has("email"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("email") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Пароль</label>
                        <div class="input-group has-validation">
                            <input
                                type="password"
                                name="password"
                                @class([
                                    "form-control",
                                    "is-invalid" => $errors->has("password")
                                ])
                                id="password"
                            >
                            @if($errors->has("password"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("password") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(!$student->isSelfStudent())
                    <div class="mb-3">
                        <label for="name" class="form-label">Группа</label>

                        <select name="student_group_id" class="form-select">
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" @if($group->id == $student->student_group_id) selected @endif>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if(!$student->isSelfStudent())
                    <div class="mb-3">
                        <label for="name" class="form-label">Преподаватель</label>

                        <select name="teacher_id" class="form-select">
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @if($teacher->id == $student->teacher_id) selected @endif>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary">
                        @isset($student)
                            Сохранить
                        @else
                            Добавить
                        @endisset
                    </button>

                    <a href="{{ route('admin.students.show') }}" class="btn btn-secondary">
                        <i class="bi fa-solid fa-arrow-left"></i>
                        Вернуться
                    </a>
                </form>
            </div>
        </div>
    </div>
</x-layout>
