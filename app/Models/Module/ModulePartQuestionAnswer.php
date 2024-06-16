<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель для ответов на вопросы к тесту раздела модуля.
 *
 * @property int $id
 * @property string $text
 * @property boolean $is_correct
 */
class ModulePartQuestionAnswer extends Model
{
    // Использование фабрики для создания экземпляров модели.
    // Фабрика - это класс, который содержит методы для создания экземпляров модели.
    use HasFactory;

    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = [
        'text',
    ];
}

