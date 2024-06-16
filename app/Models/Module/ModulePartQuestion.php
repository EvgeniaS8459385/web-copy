<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Модель для вопросов к тесту раздела модуля.
 *
 * @property int $id
 * @property ModulePartQuestionAnswer[] $answers
 */
class ModulePartQuestion extends Model
{
    // Использование фабрики для создания экземпляров модели.
    // Фабрика - это класс, который содержит методы для создания экземпляров модели.
    use HasFactory;

    // Тип вопроса, где нужно выбрать один из вариантов.
    const TYPE_SINGLE_CHOICE = 'single_choice';

    // Фабрика - это класс, который содержит методы для создания экземпляров модели.
    protected $fillable = [
        'text',
    ];

    // Отношение к ответам на вопрос.
    // Ответы на вопросы упорядочены по полю order.
    public function answers(): HasMany
    {
        // Возвращает связь один-ко-многим с моделью ModulePartQuestionAnswer.
        return $this->answersUnordered()->orderBy('order');
    }

    // Отношение к ответам на вопрос.
    // Ответы на вопросы не упорядочены.
    public function answersUnordered(): HasMany
    {
        // Возвращает связь один-ко-многим с моделью ModulePartQuestionAnswer.
        return $this->hasMany(ModulePartQuestionAnswer::class);
    }
}

