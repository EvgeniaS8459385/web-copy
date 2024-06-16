<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Модель модуля.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property ModulePart[] $parts
 * @property ModulePart[] $partsUnordered
 */
class Module extends Model
{
    // Использование фабрики для создания экземпляров модели.
    // Фабрика - это класс, который содержит методы для создания экземпляров модели.
    // Использование мягкого удаления (если строка удалена, она не удаляется из базы данных, а помечается как удаленная).
    use HasFactory, SoftDeletes;

    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = ['name', 'description'];

    // Отношение к разделам модуля.
    // Разделы упорядочены по полю order.
    public function parts(): HasMany
    {
        // Возвращает связь один-ко-многим с моделью ModulePart.
        return $this->partsUnordered()->orderBy('order');
    }

    // Отношение к разделам модуля.
    // Разделы не упорядочены.
    public function partsUnordered(): HasMany
    {
        // Возвращает связь один-ко-многим с моделью ModulePart.
        return $this->hasMany(ModulePart::class);
    }

    // Получение следующего раздела после указанного.
    public function nextPart(ModulePart $part): ?ModulePart
    {
        // Получение следующего раздела после указанного.
        // Если раздел последний, то возвращает null.
        /** @var ?ModulePart $nextPart */
        $nextPart = $this->partsUnordered()
            ->where('order', '>', $part->order)
            ->first();
        return $nextPart;
    }
}
