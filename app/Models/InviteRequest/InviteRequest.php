<?php

namespace App\Models\InviteRequest;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Модель запроса на добавление в студента преподавателю.
 *
 * @property int $id
 * @property int $student_id
 * @property int $teacher_id
 * @property bool $is_accepted
 * @property User $student
 * @property User $teacher
 */
class InviteRequest extends Model
{
    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = [
        'student_id',
        'teacher_id',
    ];

    // Связь со студентом.
    public function student(): BelongsTo
    {
        // Возвращает связь с моделью User, где student_id - внешний ключ.
        return $this->belongsTo(User::class, 'student_id');
    }

    // Связь с преподавателем.
    public function teacher(): BelongsTo
    {
        // Возвращает связь с моделью User, где teacher_id - внешний ключ.
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Принять запрос на добавление в студента преподавателю.
    public function accept(): void
    {
        // Запустить транзакцию (если хотя бы один запрос не выполнится, то все откатятся).
        DB::transaction(function () {
            // Установить статус запроса как принятый.
            $this->is_accepted = true;
            // Сохранить изменения запроса в БД.
            $this->save();

            // Установить статус принятия запроса у студента.
            $this->student->is_invite_accepted = true;
            // Сохранить изменения студента в БД.
            $this->student->save();
        });
    }

    // Отклонить запрос на добавление в студента преподавателю.
    public function decline(): void
    {
        // Запустить транзакцию (если хотя бы один запрос не выполнится, то все откатятся).
        DB::transaction(function () {
            // Установить статус запроса как не принятый.
            $this->is_accepted = false;
            // Сохранить изменения запроса в БД.
            $this->save();
            // Установить статус принятия запроса у студента.
            $this->student->is_invite_accepted = false;
            // Сохранить изменения студента в БД.
            $this->student->save();
        });
    }
}
