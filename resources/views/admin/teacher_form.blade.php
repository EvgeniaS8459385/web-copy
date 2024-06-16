@php
    /** @var \App\Models\User $teacher */

    $name = $teacher->name ?? old('name');
    $email = $teacher->email ?? old('email');

    $breadcrumbs = [
        route('admin.teachers.show') => 'Преподаватели',
    ];
    if (isset($teacher)) {
        $breadcrumbs[route('admin.teachers.edit', $teacher)] = $teacher->name;
    } else {
        $breadcrumbs[route('admin.teachers.create')] = 'Новый преподаватель';
    }
@endphp

<x-layout>
    @isset($teacher)
        <x-slot:title>{{ $teacher->name  }}</x-slot:title>
        <h1>Редактирование преподавателя</h1>
    @else
        <x-slot:title>Новый преподаватель</x-slot:title>
        <h1>Новый преподаватель</h1>
    @endisset

    <div class="container px-0">
        <div class="row gy-5">
            <div class="col-12">
                <form
                    action="{{ isset($teacher) ? route('admin.teachers.update', $teacher) : route('admin.teachers.store') }}"
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

                    @isset($teacher)
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    @else
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    @endisset

                    <a href="{{ route('admin.teachers.show') }}" class="btn btn-secondary">
                        <i class="bi fa-solid fa-arrow-left"></i>
                        Вернуться
                    </a>
                </form>
            </div>
        </div>
    </div>
</x-layout>
