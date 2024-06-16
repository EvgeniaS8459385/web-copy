@php

/** @var \App\Models\Module\Module $module */
/** @var \App\Models\Module\ModulePart $part */
/** @var \App\Models\Module\ModulePartQuestion $question */

$questionText = $question->text ?? old('text');

$breadcrumbs = [
    route('admin.modules.show') => 'Модули',
    route('admin.modules.edit', $module) => $module->name,
    route('admin.modules.parts.edit', [$module, $part]) => $part->name,
];
if (isset($question)) {
    $breadcrumbs[route('admin.modules.parts.questions.edit', [$module, $part, $question])] = 'Редактирование вопроса';
} else {
    $breadcrumbs[route('admin.modules.parts.questions.create', [$module,$part])] = 'Новый вопрос';
}
@endphp

<x-layout :breadcrumbs="$breadcrumbs">
    <div class="d-flex align-items-center">
        @isset($question)
            <x-slot:title>Редактирование вопроса</x-slot:title>
            <h1 class="flex-fill">Редактирование вопроса</h1>
        @else
            <x-slot:title>Новый вопрос</x-slot:title>
            <h1 class="flex-fill">Новый вопрос</h1>
        @endisset

        <a href="{{ route('admin.modules.parts.edit', [$module, $part]) }}" class="btn text-end btn-secondary">
            <i class="bi fa-solid fa-arrow-left"></i>
            Вернуться к этапу модуля
        </a>
    </div>

    <div class="container px-0">
        <div class="row gy-5">
            <div class="col-12">
                <form
                    action="{{ isset($question)
                            ? route('admin.modules.parts.questions.update', [$module, $part, $question])
                            : route('admin.modules.parts.questions.store', [$module, $part]) }}"
                    method="post"
                >
                    @csrf

                    <div class="mb-3">
                        <label for="content" class="form-label">Текст вопроса</label>
                        <div class="input-group has-validation">
                            <textarea class="tinymce-editor" name="text">{!! $questionText !!}</textarea>

                            @if($errors->has("text"))
                                <div class="invalid-feedback">
                                    {{ $errors->first("text") }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>

            <div class="col-12">
                <div class="d-flex align-items-center">
                    <h2 class="flex-fill">Ответы</h2>
                    @isset($question)
                        <a href="{{ route('admin.modules.parts.questions.answers.create', [$module, $part, $question]) }}"
                           class="btn text-end btn-primary">
                            <i class="bi fa-solid fa-plus"></i>
                            Добавить ответ
                        </a>
                    @endisset
                </div>
                @empty($question)
                    <p class="alert alert-info">
                        Чтобы добавить вопросы сначала его нужно сохранить этап модуля.
                    </p>
                @else
                    @if(count($answers) === 0)
                        <p class="alert alert-info">
                            В этом вопросе пока еще нет ответов.
                        </p>
                    @else
                        <table class="table table-striped table-hover table-with-actions">
                            <thead>
                            <tr>
                                <th>Ответ</th>
                                <th>Правильный</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($answers as $idx=>$answer)
                                <tr>
                                    <td>{{ $idx + 1 }}. {!! $answer->text !!}</td>
                                    <td>{{ $answer->is_correct ? 'Да' : '' }}</td>
                                    <td class="actions-cell" style="width:450px;">
                                        <form class="d-inline"
                                              action="{{ route('admin.modules.parts.questions.answers.reduceOrder', [$module, $part, $question, $answer]) }}"
                                              method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary" @disabled($idx===0)>
                                                <i class="bi fa-solid fa-arrow-up"></i>
                                            </button>
                                        </form>
                                        <form class="d-inline"
                                              action="{{ route('admin.modules.parts.questions.answers.raiseOrder', [$module, $part, $question, $answer]) }}"
                                              method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary" @disabled($idx===count($answers)-1)>
                                                <i class="bi fa-solid fa-arrow-down"></i>
                                            </button>
                                        </form>

                                        <form class="d-inline"
                                              action="{{ route('admin.modules.parts.questions.answers.setIsCorrect', [$module, $part, $question, $answer]) }}"
                                              method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary">
                                                <i class="bi fa-solid fa-check"></i>
                                                Пометить как правильный
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.modules.parts.questions.answers.edit', [$module, $part, $question, $answer]) }}"
                                           class="btn btn-primary">
                                            <i class="bi fa-solid fa-pencil"></i>
                                        </a>
                                        <form class="d-inline" action="{{ route('admin.modules.parts.questions.answers.delete', [$module, $part, $question, $answer]) }}"
                                              method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi fa-solid fa-trash"></i>
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
