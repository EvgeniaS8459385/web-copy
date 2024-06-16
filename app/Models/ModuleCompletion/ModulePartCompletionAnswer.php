<?php

namespace App\Models\ModuleCompletion;

use App\Models\Module\ModulePartQuestionAnswer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель для ответа, который дал студент на вопрос в тесте раздела модуля.
 *
 * @property int $id
 * @property int $module_part_question_id
 * @property int $module_part_question_answer_id
 * @property ModulePartQuestionAnswer $choiseAnswer
 */
class ModulePartCompletionAnswer extends Model
{
    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = [
        'module_part_question_id',
        'module_part_question_answer_id',
    ];

    // Отношение к варианту ответа на вопрос.
    public function choiseAnswer(): BelongsTo
    {
        // Возвращает связь с моделью варианта ответа на вопрос.
        // Второй параметр - имя столбца в текущей модели, по которому идет связь.
        return $this->belongsTo(ModulePartQuestionAnswer::class, 'module_part_question_answer_id');
    }

    // Проверка, является ли ответ на вопрос где нужно выбрать один из вариантов.
    public function isChoiseAnswer(): bool
    {
        return $this->module_part_question_answer_id !== null;
    }

    // Проверка, является ли ответ на вопрос правильным.
    public function isCorrect(): bool
    {
        return $this->choiseAnswer->is_correct;
    }
}
