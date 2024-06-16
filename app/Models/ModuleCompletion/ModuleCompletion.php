<?php

namespace App\Models\ModuleCompletion;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Модель прогресса студента по модулю.
 *
 * @property int $id
 * @property int $student_id
 * @property int $module_id
 * @property int $module_part_id
 * @property Carbon $created_at
 * @property Carbon $completed_at
 * @property User $student
 * @property Module $module
 * @property ModulePart $modulePart
 */
class ModuleCompletion extends Model
{
    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = [
        'student_id',
        'module_id',
        'module_part_id',
    ];

    // Функция, которая вызывается при загрузке модели для определения обработчиков событий.
    static protected function boot(): void
    {
        parent::boot();

        // Событие, которое вызывается после создания новой записи в таблице.
        static::created(function (ModuleCompletion $moduleCompletion) {
            // Создаем запись о прогрессе первой части модуля.
            ModulePartCompletion::create([
                'student_id' => $moduleCompletion->student_id,
                'module_id' => $moduleCompletion->module_id,
                'module_part_id' => $moduleCompletion->module_part_id,
            ]);
        });
    }

    // Отношение к студенту.
    public function student(): BelongsTo
    {
        // Возвращает связь один-ко-многим с моделью User.
        // Второй аргумент - название столбца в таблице, который является внешним ключом.
        return $this->belongsTo(User::class, 'student_id');
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

    // Получить все прогрессы прохождения разделов модуля студентом.
    public function modulePartCompletions(): Collection
    {
        return ModulePartCompletion::where('student_id', $this->student_id)
            ->where('module_id', $this->module_id)
            ->get();
    }

    // Получить прогресс прохождения раздела модуля студентом, который сейчас проходится.
    public function currentModulePartCompletion(): ?ModulePartCompletion
    {
        return $this->modulePartCompletions()
            ->where('module_part_id', $this->module_part_id)
            ->first();
    }

    // Функция завершения раздела модуля студентом.
    // Принимает на вход раздел модуля, который нужно завершить.
    public function completePart(ModulePart $part): void
    {
        // Запускаем транзакцию (если хотя бы один запрос внутри транзакции завершится неудачно, то все запросы откатываются).
        DB::transaction(function () use ($part) {
            // Получаем прогресс прохождения раздела модуля студентом, который сейчас проходится.
            $modulePartCompletion = $this->currentModulePartCompletion();

            // Завершаем прохождение раздела.
            $modulePartCompletion->complete();

            // Получаем следующий раздел модуля.
            $nextPart = $this->module->nextPart($part);

            // Если следующего раздела нет, то завершаем прохождение модуля.
            if ($nextPart === null) {
                $this->complete();
                return;
            }

            // Назначаем следующий раздел модуля текущим.
            $this->modulePart()->associate($nextPart);

            // Сохраняем изменения.
            $this->save();

            // Создаем запись о прогрессе прохождения следующего раздела.
            ModulePartCompletion::create([
                'student_id' => $this->student_id,
                'module_id' => $this->module_id,
                'module_part_id' => $nextPart->id,
            ]);
        });
    }

    // Функция завершения прохождения модуля студентом.
    private function complete(): void
    {
        $this->completed_at = now();
        $this->save();
    }

    // Функция для проверки завершенности прохождения модуля студентом.
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }
}
