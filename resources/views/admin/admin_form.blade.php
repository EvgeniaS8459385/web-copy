@php
    /** @var \App\Models\User $admin */

    $name = $admin->name ?? old('name');
    $email = $admin->email ?? old('email');
@endphp

<x-layout>
    @isset($admin)
        <x-slot:title>{{ $admin->name  }}</x-slot:title>
        <h1>Редактирование администратора</h1>
    @else
        <x-slot:title>Новый администратор</x-slot:title>
        <h1>Новый администратор</h1>
    @endisset

        <div class="container px-0">
            <div class="row gy-5">
                <div class="col-12">
                    <form
                        action="{{ isset($admin) ? route('admin.admins.update', $admin) : route('admin.admins.store') }}"
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

                        @isset($admin)
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        @else
                            <button type="submit" class="btn btn-primary">Добавить</button>
                        @endisset

                        <a href="{{ route('admin.admins.show') }}" class="btn btn-secondary">
                            <i class="bi fa-solid fa-arrow-left"></i>
                            Вернуться
                        </a>
                    </form>
                </div>
            </div>
        </div>
</x-layout>
