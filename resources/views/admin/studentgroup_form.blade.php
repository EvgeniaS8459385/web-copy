@php
    /** @var \App\Models\StudentGroup\StudentGroup $group */

    $name = $group->name ?? old('name');

    $breadcrumbs = [
        route('admin.studentgroups.show') => 'Группы студентов',
    ];
    if (isset($group)) {
        $breadcrumbs[route('admin.studentgroups.edit', $group)] = $group->name;
    } else {
        $breadcrumbs[route('admin.studentgroups.create')] = 'Новая группа';
    }
@endphp

<x-layout>
    @isset($group)
        <x-slot:title>{{ $group->name  }}</x-slot:title>
        <h1>Редактирование группы</h1>
    @else
        <x-slot:title>Новая группа</x-slot:title>
        <h1>Новый группа</h1>
    @endisset

    <div class="container px-0">
        <div class="row gy-5">
            <div class="col-12">
                <form
                        action="{{ isset($group) ? route('admin.studentgroups.update', $group) : route('admin.studentgroups.store') }}"
                        method="post"
                >
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Название</label>
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

                    <button type="submit" class="btn btn-primary">
                        @isset($group)
                            Сохранить
                        @else
                            Добавить
                        @endisset
                    </button>

                    <a href="{{ route('admin.studentgroups.show') }}" class="btn btn-secondary">
                        <i class="bi fa-solid fa-arrow-left"></i>
                        Вернуться
                    </a>
                </form>
            </div>
        </div>
    </div>
</x-layout>
