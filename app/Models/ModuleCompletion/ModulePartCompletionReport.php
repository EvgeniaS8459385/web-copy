<?php

namespace App\Models\ModuleCompletion;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с отчетами по выполнению раздела модуля.
 *
 * @property int $id
 * @property string $file
 */
class ModulePartCompletionReport extends Model
{
    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = [
        'file',
    ];
}
