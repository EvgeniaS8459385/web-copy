<?php

namespace App\Models\StudentGroup;

use App\Models\Module\ModulePart;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель группы студентов
 *
 * @property int $id
 * @property string $name
 * @property User[]|Collection $students
 */
class StudentGroup extends Model
{
    // Использование фабрики для создания экземпляров модели.
    // Фабрика - это класс, который содержит методы для создания экземпляров модели.
    use HasFactory;

    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = ['name'];

    // Функция, которая возвращает процент завершенности разделов модулей группой студентов.
    public function completePercent(): float
    {
        // Получаем студентов группы.
        $students = $this->students;

        // Если студентов нет, то возвращаем 0.
        if ($students->count() === 0) {
            return 0.00;
        }

        // Получаем количество разделов модулей.
        $modulePartsCount = ModulePart::count();

        // Получаем количество завершенных разделов модулей студентами.
        $completedModulePartsCount = 0;
        foreach ($students as $student) {
            $completedModulePartsCount += $student->completedModuleParts()->count();
        }

        // Возвращаем процент завершенности разделов модулей группой студентов.
        // Процент завершенности = количество завершенных разделов модулей / (количество студентов * количество разделов модулей) * 100.
        return round($completedModulePartsCount / ($students->count() * $modulePartsCount) * 100, 2);
    }

    // Отношение один ко многим. Группа студентов имеет много студентов.
    public function students(): HasMany {
        // Возвращает связь с моделью User.
        return $this->hasMany(User::class);
    }
}
