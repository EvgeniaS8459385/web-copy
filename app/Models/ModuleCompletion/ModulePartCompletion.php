<?php

namespace App\Models\ModuleCompletion;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\Module\ModulePartQuestion;
use App\Models\Module\ModulePartQuestionAnswer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Модель прогресса прохождения раздела модуля студентом.
 *
 * @property int $id
 * @property int $student_id
 * @property int $module_id
 * @property int $module_part_id
 * @property int $report_id
 * @property Carbon $created_at
 * @property Carbon $theory_completed_at
 * @property Carbon $test_started_at
 * @property Carbon $test_completed_at
 * @property Carbon $completed_at
 * @property User $student
 * @property Module $module
 * @property ModulePart $modulePart
 * @property ModulePartCompletionAnswer[]|HasMany $answers
 * @property ModulePartCompletionReport $report
 */
class ModulePartCompletion extends Model
{
    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = [
        'student_id',
        'module_id',
        'module_part_id',
    ];

    // Поля, которые будут преобразованы в экземпляры Carbon (класс по работе с датой и временем).
    protected function casts()
    {
        return [
            'theory_completed_at' => 'datetime',
            'test_started_at' => 'datetime',
            'test_completed_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // Отношение к модулю.
    public function module(): BelongsTo
    {
        // Возвращает связь один-ко-многим с моделью Module.
        return $this->belongsTo(Module::class);
    }

    // Отношение к разделу модуля.
    public function modulePart(): BelongsTo
    {
        // Возвращает связь один-ко-многим с моделью ModulePart.
        return $this->belongsTo(ModulePart::class);
    }

    // Отношение к студенту.
    public function student(): BelongsTo
    {
        // Возвращает связь один-ко-многим с моделью User.
        // Второй аргумент - название столбца в таблице, который связан с id студента.
        return $this->belongsTo(User::class, 'student_id');
    }

    // Отношение к ответам студента.
    public function answers(): HasMany
    {
        // Возвращает связь один-ко-многим с моделью ModulePartCompletionAnswer.
        return $this->hasMany(ModulePartCompletionAnswer::class);
    }

    // Отношение к отчету о прохождении раздела.
    public function report(): BelongsTo
    {
        // Возвращает связь один-ко-многим с моделью ModulePartCompletionReport.
        // Второй аргумент - название столбца в таблице, который связан с id отчета.
        return $this->belongsTo(ModulePartCompletionReport::class, 'report_id');
    }

    // Функция для начала прохождения теста.
    public function beginTest(): void
    {
        $this->test_started_at = now();
        $this->save();
    }

    // Функция для завершения прохождения теста.
    public function endTest(): void
    {
        $this->test_completed_at = now();
        $this->save();
    }

    // Функция для прикрепления отчета о прохождении раздела.
    // Принимает файл отчета.
    public function attachReport(UploadedFile $file): void
    {
        // Запускает транзакцию (если что-то пойдет не так, то все изменения откатятся).
        DB::transaction(function () use ($file) {
            // Создает отчет о прохождении раздела.
            /** @var ModulePartCompletionReport $report */
            $report = $this->report()->create([
                'file' => $file->store('reports'),
            ]);
            // Присваивает id отчета к текущему прогрессу прохождения раздела.
            $this->report_id = $report->id;
            // Сохраняет изменения.
            $this->save();
        });
    }

    // Функция для ответа студентом на вопрос.
    // Принимает вопрос и ответ, который дал студент.
    public function attachAnswer(
        ModulePartQuestion       $question,
        ModulePartQuestionAnswer $answer,
    ): void
    {
        // Создает ответ студента на вопрос.
        $this->answers()->create([
            'module_part_question_id' => $question->id,
            'module_part_question_answer_id' => $answer->id,
        ]);
    }

    // Функция для получения ответа студента на вопрос.
    // Принимает вопрос.
    // Возвращает ответ студента на вопрос или null, если ответа нет.
    public function answer(ModulePartQuestion $question): ?ModulePartCompletionAnswer
    {
        /** @var ?ModulePartCompletionAnswer $answer */
        $answer = $this->answers()
            ->where('module_part_question_id', $question->id)
            ->first();
        return $answer;
    }

    // Функция для получения количества правильных ответов студента на тест.
    public function correctAnswersCount(): int
    {
        return $this->answers
            // Фильтрует ответы студента, оставляя только правильные.
            ->filter(function (ModulePartCompletionAnswer $answer) {
                return $answer->isCorrect();
            })
            // Подсчитывает количество правильных ответов.
            ->count();
    }

    // Функция подсчета баллов студента за тест.
    public function points(): float
    {
        // Баллы = количество правильных ответов / общее количество вопросов * 100.
        return round($this->correctAnswersCount() / $this->modulePart->questionsCount() * 100, 2);
    }

    // Функция для проверки, что теория завершена.
    public function isTheoryCompleted(): bool
    {
        return $this->theory_completed_at !== null;
    }

    // Функция для проверки, что тест начат.
    public function isTestStarted(): bool
    {
        return $this->test_started_at !== null;
    }

    // Функция для завершения теории.
    public function completeTheory(): void
    {
        $this->theory_completed_at = now();
        // Сохраняем изменения в базе данных.
        $this->save();
    }

    // Функция для проверки, что тест завершен.
    public function isTestEnded(): bool
    {
        return $this->test_completed_at !== null;
    }

    // Функция для проверки, что время на тест истекло.
    public function isTimeOver(): bool
    {
        // Возвращает true, если разница между временем начала теста и текущим временем больше, чем лимит времени на тест.
        return $this->test_started_at->diffInSeconds(now()) > $this->modulePart->time_limit;
    }

    // Функция для проверки, что раздел завершен.
    public function isReportAttached(): bool
    {
        return $this->report_id !== null;
    }

    // Функция для завершения прохождения раздела.
    public function complete(): void
    {
        $this->completed_at = now();
        $this->save();
    }

    // Функция для проверки, что раздел завершен.
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    // Функция для подсчета времени, проведенного на тесте.
    public function timeSpentOnTest(): int
    {
        // Возвращает 0, если тест не начат или не завершен.
        if ($this->test_started_at === null || $this->test_completed_at === null) {
            return 0;
        }
        // Возвращает разницу в секундах между временем начала теста и временем завершения теста.
        return $this->test_started_at->diffInSeconds($this->test_completed_at);
    }
}
