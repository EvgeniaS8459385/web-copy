@php
    /** @var \App\Models\Module\Module $module */

    $name = $module->name ?? old('name');
    $description = $module->description ?? old('description');
    $breadcrumbs = [
        route('admin.modules.show') => 'Модули',
    ];
    if (isset($module)) {
        $breadcrumbs[route('admin.modules.edit', $module)] = $module->name;
    } else {
        $breadcrumbs[route('admin.modules.create')] = 'Новый модуль';
    }
@endphp

<x-layout :breadcrumbs="$breadcrumbs">
    <div class="d-flex align-items-center">
        @isset($module)
            <x-slot:title>{{ $module->name  }}</x-slot:title>
            <h1 class="flex-fill">Редактирование модуля</h1>
        @else
            <x-slot:title>Новый модуль</x-slot:title>
            <h1 class="flex-fill">Новый модуль</h1>
        @endisset

        <a href="{{ route('admin.modules.show') }}" class="btn text-end btn-secondary">
            <i class="bi fa-solid fa-arrow-left"></i>
            Вернуться к списку модулей
        </a>
    </div>

    <div class="container px-0">
        <div class="row gy-5">
            <div class="col-12">
                <form
                    action="{{ isset($module) ? route('admin.modules.update', $module) : route('admin.modules.store') }}"
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

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <div class="input-group has-validation">
                            <textarea
                                name="description"
                                @class([
                                    "form-control",
                                    "is-invalid" => $errors->has("description")
                                ])
                                id="description"
                            >{{$description}}</textarea>
                            @if($errors->has("description"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("description") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <h2 class="flex-fill">Этапы модуля</h2>
                    @isset($module)
                    <a href="{{ route('admin.modules.parts.create', $module) }}" class="btn text-end btn-primary">
                        <i class="bi fa-solid fa-plus"></i>
                        Добавить этап
                    </a>
                    @endisset
                </div>

                @empty($module)
                    <p class="alert alert-info">
                        Чтобы добавить этапы модуля сначала нужно сохранить модуль.
                    </p>
                @else
                    @if(count($parts) === 0)
                        <p class="alert alert-info">
                            В этом модуле пока нет этапов.
                        </p>
                    @else
                        <table class="table table-striped table-hover table-with-actions">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($parts as $idx=>$part)
                                <tr>
                                    <td>{{ $idx + 1 }}. {{ $part->name }}</td>
                                    <td class="actions-cell" style="width:400px;">
                                        <form class="d-inline" action="{{ route('admin.modules.parts.reduceOrder', [$module, $part]) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary" @disabled($idx===0)>
                                                <i class="bi fa-solid fa-arrow-up"></i>
                                            </button>
                                        </form>
                                        <form class="d-inline" action="{{ route('admin.modules.parts.raiseOrder', [$module, $part]) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary" @disabled($idx===count($parts)-1)>
                                                <i class="bi fa-solid fa-arrow-down"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.modules.parts.edit', [$module, $part]) }}" class="btn btn-primary">
                                            <i class="bi fa-solid fa-pencil"></i>
                                            Редактировать
                                        </a>
                                        <form class="d-inline" action="{{ route('admin.modules.parts.delete', [$module, $part]) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi fa-solid fa-trash"></i>
                                                Удалить
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                @endempty
            </div>
        </div>
    </div>
</x-layout>
