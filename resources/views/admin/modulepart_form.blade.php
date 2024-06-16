@php
/** @var \App\Models\Module\Module $module */
/** @var \App\Models\Module\ModulePart $part */

$name = $part->name ?? old('name');
$content = $part->content ?? old('content');

$breadcrumbs = [
    route('admin.modules.show') => 'Модули',
    route('admin.modules.edit', $module) => $module->name,
];
if (isset($part)) {
    $breadcrumbs[route('admin.modules.parts.edit', [$module, $part])] = $part->name;
} else {
    $breadcrumbs[route('admin.modules.parts.create', $module)] = 'Новый этап модуля';
}
@endphp

<x-layout :breadcrumbs="$breadcrumbs">
    <div class="d-flex align-items-center">
        @isset($part)
            <x-slot:title>{{ $part->name  }}</x-slot:title>
            <h1 class="flex-fill">Редактирование этапа модуля</h1>
        @else
            <x-slot:title>Новый этап модуля</x-slot:title>
            <h1 class="flex-fill">Новый этап модуля</h1>
        @endisset

        <a href="{{ route('admin.modules.edit', $module) }}" class="btn text-end btn-secondary">
            <i class="bi fa-solid fa-arrow-left"></i>
            Вернуться к модулю
        </a>
    </div>

    <div class="container px-0">
        <div class="row gy-5">
            <div class="col-12">
                <form
                    action="{{ isset($part) ? route('admin.modules.parts.update', [$module, $part]) : route('admin.modules.parts.store', $module) }}"
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
                        <label for="content" class="form-label">Содержание</label>
                        <div class="input-group has-validation">
                            <textarea class="tinymce-editor w-100" name="content">{!! $content !!}</textarea>

                            @if($errors->has("content"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("content") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="time_limit" class="form-label">Время на тест (сек)</label>
                        <div class="input-group has-validation">
                            <input
                                type="number"
                                name="time_limit"
                                @class([
                                    "form-control",
                                    "is-invalid" => $errors->has("time_limit")
                                ])
                                id="time_limit"
                                value="{{ $part->time_limit ?? old('time_limit') }}"
                            >
                            @if($errors->has("time_limit"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("time_limit") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="date_limit" class="form-label">Дата окончания сдачи</label>
                        <div class="input-group has-validation">
                            <input
                                type="date"
                                name="date_limit"
                                @class([
                                    "form-control",
                                    "is-invalid" => $errors->has("date_limit")
                                ])
                                id="date_limit"
                                value="{{ $part->date_limit->format('Y-m-d') ?? old('date_limit') }}"
                            >
                            @if($errors->has("date_limit"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("date_limit") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>

            <div class="col-12">
                <div class="d-flex align-items-center">
                    <h2 class="flex-fill">Вопросы</h2>
                    @isset($part)
                        <a href="{{ route('admin.modules.parts.questions.create', [$module, $part]) }}" class="btn text-end btn-primary">
                            <i class="bi fa-solid fa-plus"></i>
                            Добавить вопрос
                        </a>
                    @endisset
                </div>
                @empty($part)
                    <p class="alert alert-info">
                        Чтобы добавить вопросы сначала его нужно сохранить этап модуля.
                    </p>
                @else
                    @if(count($questions) === 0)
                        <p class="alert alert-info">
                            На этом этапе модуля пока нет вопросов.
                        </p>
                    @else
                        <table class="table table-striped table-hover table-with-actions">
                            <thead>
                            <tr>
                                <th>Вопрос</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($questions as $idx=>$question)
                                <tr>
                                    <td>{{ $idx + 1 }}. {!! $question->text !!}</td>
                                    <td class="actions-cell" style="width:400px;">
                                        <form class="d-inline" action="{{ route('admin.modules.parts.questions.reduceOrder', [$module, $part, $question]) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary" @disabled($idx===0)>
                                                <i class="bi fa-solid fa-arrow-up"></i>
                                            </button>
                                        </form>
                                        <form class="d-inline" action="{{ route('admin.modules.parts.questions.raiseOrder', [$module, $part, $question]) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary" @disabled($idx===count($questions)-1)>
                                                <i class="bi fa-solid fa-arrow-down"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.modules.parts.questions.edit', [$module, $part, $question]) }}" class="btn btn-primary">
                                            <i class="bi fa-solid fa-pencil"></i>
                                            Редактировать
                                        </a>
                                        <form class="d-inline" action="{{ route('admin.modules.parts.questions.delete', [$module, $part, $question]) }}" method="post">
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
