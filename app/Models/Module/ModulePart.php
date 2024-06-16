<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;


/**
 * Модель раздела модуля.
 *
 * @property int $id
 * @property string $name
 * @property int $time_limit
 * @property int $order
 * @property int $module_id
 * @property string $content
 * @property Module $module
 * @property Carbon $created_at
 * @property Carbon $date_limit
 * @property ModulePartQuestion[] $questions
 * @property ModulePartQuestion[] $questionsUnordered
 */
class ModulePart extends Model
{
    // Использование фабрики для создания экземпляров модели.
    // Фабрика - это класс, который содержит методы для создания экземпляров модели.
    // Использование мягкого удаления (если строка удалена, она не удаляется из базы данных, а помечается как удаленная).
    use HasFactory, SoftDeletes;

    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = [
        'name',
        'content',
        'time_limit',
        'date_limit',
    ];

    // Поля, которые будут преобразованы в экземпляры Carbon (класс по работе с датой и временем).
    protected function casts(): array
    {
        return [
            'date_limit' => 'datetime',
        ];
    }

    // Отношение к модулю.
    public function module(): BelongsTo
    {
        // Возвращает связь один-ко-многим с моделью Module.
        return $this->belongsTo(Module::class);
    }

    // Отношение к вопросам раздела.
    // Вопросы упорядочены по полю order.
    public function questions(): HasMany
    {
        // Возвращает связь один-ко-многим с моделью ModulePartQuestion.
        return $this->questionsUnordered()->orderBy('order');
    }

    // Отношение к вопросам раздела.
    // Вопросы не упорядочены.
    public function questionsUnordered(): HasMany
    {
        // Возвращает связь один-ко-многим с моделью ModulePartQuestion.
        return $this->hasMany(ModulePartQuestion::class);
    }

    // Функция, которая возвращает количество вопросов раздела.
    public function questionsCount(): int
    {
        return $this->questions()->count();
    }

    // Функция проверяет, что дата сдачи раздела прошла.
    // (студент, который не сдал раздел вовремя, считается задолжником).
    public function isDateOver(): bool
    {
        // Возвращает true, если дата сдачи раздела прошла.
        return $this->date_limit->isPast();
    }
}

