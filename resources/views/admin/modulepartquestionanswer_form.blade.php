@php

    /** @var \App\Models\Module\Module $module */
    /** @var \App\Models\Module\ModulePart $part */
    /** @var \App\Models\Module\ModulePartQuestion $question */
    /** @var \App\Models\Module\ModulePartQuestionAnswer $answer */

    $answerText = $answer->text ?? old('text');
    $isCorrect = $answer->is_correct ?? old('is_correct');

    $breadcrumbs = [
        route('admin.modules.show') => 'Модули',
        route('admin.modules.edit', $module) => $module->name,
        route('admin.modules.parts.edit', [$module, $part]) => $part->name,
        route('admin.modules.parts.questions.edit', [$module, $part, $question]) => 'Редактирование вопроса',
    ];
    if (isset($answer)) {
        $breadcrumbs[route('admin.modules.parts.questions.answers.edit', [$module, $part, $question, $answer])] = 'Редактирование ответа';
    } else {
        $breadcrumbs[route('admin.modules.parts.questions.answers.create', [$module, $part, $question])] = 'Новый ответ';
    }
@endphp

<x-layout :breadcrumbs="$breadcrumbs">
    <div class="d-flex align-items-center">
        @isset($question)
            <x-slot:title>Редактирование ответа</x-slot:title>
            <h1 class="flex-fill">Редактирование ответа</h1>
        @else
            <x-slot:title>Новый ответ</x-slot:title>
            <h1 class="flex-fill">Новый ответ</h1>
        @endisset

        <a href="{{ route('admin.modules.parts.questions.edit', [$module, $part, $question]) }}" class="btn text-end btn-secondary">
            <i class="bi fa-solid fa-arrow-left"></i>
            Вернуться к вопросу
        </a>
    </div>

    <div class="container px-0">
        <div class="row gy-5">
            <div class="col-12">
                <form
                    action="{{ isset($answer)
                            ? route('admin.modules.parts.questions.answers.update', [$module, $part, $question, $answer])
                            : route('admin.modules.parts.questions.answers.store', [$module, $part, $question]) }}"
                    method="post"
                >
                    @csrf

                    <div class="mb-3">
                        <label for="content" class="form-label">Текст ответа</label>
                        <div class="input-group has-validation">
                            <textarea type="text" name="text" class="form-control tinymce-editor" id="text">{!! $answerText !!}</textarea>

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
        </div>
    </div>
</x-layout>
