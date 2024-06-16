@php
    use App\Models\Module\Module;
    use App\Models\Module\ModulePart;
    use App\Models\Module\ModulePartQuestion;
    use App\Models\ModuleCompletion\ModulePartCompletion;

    /** @var Module $module */
    /** @var ModulePart $modulePart */
    /** @var ModulePartQuestion[] $questions */
    /** @var ModulePartCompletion $modulePartCompletion */
@endphp
<x-layout>
    <x-slot:title>{{ $modulePart->name }}</x-slot:title>

    <x-student.modulesidebar :module="$module" :part="$modulePart"/>

    <div class="d-flex align-items-center">
        <h1>{{ $modulePart->name }}: тестирование</h1>
        <div class="flex-grow-1 text-end">
            <i class="fa-solid fa-stopwatch-20"></i>
            Времени на тест:
            <x-countdown :seconds="$modulePart->time_limit" :start="$modulePartCompletion->test_started_at" />
        </div>
    </div>

    <form action="{{ route('student.modules.parts.endTest', $module) }}" method="post">
        @csrf
        @if(count($modulePart->questions) > 0)
            @foreach ($modulePart->questions as $question)
                <div class="mb-3">
                    <h3>{!! $question->text !!}</h3>
                    @foreach ($question->answers as $answer)
                        @php
                            $studentAnswer = $modulePartCompletion->answer($question);
                            $checked = ($studentAnswer && $studentAnswer->isChoiseAnswer()) || (old('answers.' . $question->id) == $answer->id);
                        @endphp
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="answers[{{ $question->id }}]"
                                id="answer-{{ $answer->id }}"
                                value="{{ $answer->id }}"
                                @checked($checked)
                            >
                            <label class="form-check label" for="answer-{{ $answer->id }}">
                                {!! $answer->text !!}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('answers.' . $question->id)
                <div class="alert alert-danger">
                    Ответ на вопрос обязателен
                </div>
                @enderror
            @endforeach
        @endif

        <div class="text-center">
            <button class="btn btn-primary">
                Завершить
            </button>
        </div>
    </form>
</x-layout>
