<?php

namespace App\Models;

use App\Models\ChatMessage\ChatMessage;
use App\Models\InviteRequest\InviteRequest;
use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\ModuleCompletion\ModuleCompletion;
use App\Models\ModuleCompletion\ModulePartCompletion;
use App\Models\StudentGroup\StudentGroup;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Модель пользователя.
 * Пользователь может быть администратором, учителем или студентом (в ВУЗе или на самообучении).
 *
 * @property int $id
 * @property int $student_group_id
 * @property int $teacher_id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string $password
 * @property string $remember_token
 * @property string $email_verified_at
 * @property int $is_self_student
 * @property int $is_invite_accepted
 * @property StudentGroup $studentGroup
 * @property User $teacher
 * @property ChatMessage[] $chatMessages
 */
class User extends Authenticatable implements MustVerifyEmail
{
    // Использование фабрики для создания экземпляров модели.
    // Фабрика - это класс, который содержит методы для создания экземпляров модели.
    // Использование уведомлений (например, отправка уведомлений на почту).
    // Использование мягкого удаления (если строка удалена, то она не удаляется физически из базы данных).
    use HasFactory, Notifiable, SoftDeletes;

    // Роли пользователей
    const ROLE_ADMIN = 'admin';
    const ROLE_STUDENT = 'student';
    const ROLE_TEACHER = 'teacher';

    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = [
        'name',
        'email',
        'password',
        'student_group_id',
        'teacher_id',
        'role',
        'is_self_student',
    ];

    // Поля, которые скрыты при преобразовании модели в массив или JSON.
    protected $hidden = [
        'password',
        'remember_token',
        'role',
    ];

    // Поля, которые должны быть преобразованы к нативным типам.
    protected function casts(): array
    {
        return [
            // Поле email_verified_at будет преобразовано в экземпляр Carbon (класс для работы с датами).
            'email_verified_at' => 'datetime',
            // Поле password будет зашифровано при сохранении в БД и дешифровано при получении из БД.
            'password' => 'hashed',
        ];
    }

    // Функция, которая вызывается при загрузке модели (в ней можно регистрировать обработчики событий).
    static protected function boot(): void
    {
        parent::boot();

        // Обработчик события создания пользователя.
        static::created(function (User $user) {
            // Если пользователь студент и не самообучается, то создаем запрос на приглашение преподавателю.
            if ($user->isStudent() && !$user->isSelfStudent()) {
                InviteRequest::create([
                    'student_id' => $user->id,
                    'teacher_id' => $user->teacher_id,
                ]);
            }
        });
    }

    // Функция возвращает все сообщения, где пользователь является отправителем или получателем.
    public function chatMessages(): Collection
    {
        return ChatMessage::where('sender_id', $this->id)
            ->orWhere('receiver_id', $this->id)
            ->get();
    }

    // Отношение к группе студентов. Пользователь может быть студентом только одной группы.
    public function studentGroup(): BelongsTo
    {
        // Возвращает связь "один ко многим" с моделью StudentGroup.
        return $this->belongsTo(StudentGroup::class);
    }

    // Отношение к преподавателю. Пользователь может быть студентом только одного преподавателя.
    public function teacher(): BelongsTo
    {
        // Возвращает связь "один ко многим" с моделью User.
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Отношение к модели прогресса прохождения модулей.
    public function moduleCompletions(): HasMany
    {
        // Возвращает связь "один ко многим" с моделью ModuleCompletion.
        return $this->hasMany(ModuleCompletion::class, 'student_id');
    }

    // Отношение к модели прогресса прохождения разделов модулей.
    public function modulePartCompletions(): HasMany
    {
        // Возвращает связь "один ко многим" с моделью ModulePartCompletion.
        return $this->hasMany(ModulePartCompletion::class, 'student_id');
    }

    // Отношение к завершенным разделам модулей.
    public function completedModuleParts(): HasMany
    {
        // Возвращает связь "один ко многим" с моделью ModulePartCompletion, которые завершены.
        return $this->modulePartCompletions()
            ->where('completed_at', '!=', null);
    }

    // Функция возвращает прогресс прохождения модуля.
    // Принимает модель модуля.
    public function moduleCompletion(Module $module): ?ModuleCompletion
    {
        // Ищем прогресс прохождения модуля пользователя в базе данных.
        // Если не найдено, то возвращаем null.
        /** @var ModuleCompletion|null $moduleCompletion */
        $moduleCompletion = $this->moduleCompletions()
            ->where('module_id', $module->id)
            ->first();
        return $moduleCompletion;
    }

    // Функция возвращает true, если пользователь является администратором.
    public function isAdmin(): bool
    {
        return static::ROLE_ADMIN === $this->role;
    }

    // Функция возвращает true, если пользователь является преподавателем.
    public function isTeacher(): bool
    {
        return static::ROLE_TEACHER === $this->role;
    }

    // Функция возвращает true, если пользователь является студентом.
    // Если пользователь самообучается, то он также считается студентом.
    public function isStudent(): bool
    {
        return static::ROLE_STUDENT === $this->role || $this->isSelfStudent();
    }

    // Функция возвращает true, если пользователь самообучается.
    public function isSelfStudent(): bool
    {
        return $this->is_self_student == true;
    }

    // Функция возвращает true, если преподаватель принял студента.
    public function isInviteAccepted(): bool
    {
        return $this->is_invite_accepted === 1;
    }

    // Функция возвращает true, если преподаватель отклонил студента.
    public function isInviteDeclined(): bool
    {
        return $this->is_invite_accepted === 0;
    }

    // Функция возвращает процент завершения модулей студентом.
    public function completePercent(): float
    {
        // Процент завершения = количество завершенных разделов модулей / общее количество разделов * 100.
        return round($this->completedModuleParts()->count() / ModulePart::count() * 100, 2);
    }

    // Функция возвращает количество баллов студента от 0 до 5.
    public function points(): float
    {
        // Получаем все завершенные разделы модулей студента.
        $completions = $this->modulePartCompletions()->get();

        // Если студент не завершил ни одного раздела, то возвращаем 0 баллов.
        if ($completions->count() === 0) {
            return 0;
        }

        // Суммируем баллы всех завершенных разделов.
        // За каждый завершенный раздел студент получает 100 баллов.
        $sum = $completions->reduce(fn ($carry, ModulePartCompletion $completion) => $carry + $completion->points(), 0);

        // Возвращаем среднее количество баллов за все завершенные разделы / 100 * 5.
        return round($sum / $completions->count() / 100 * 5, 2);
    }

    // Функция возвращает общее количество баллов студента для всех завершенных разделов.
    public function pointsAbsolute(): float {
        // Получаем все завершенные разделы модулей студента.
        $completions = $this->modulePartCompletions()->get();

        // Если студент не завершил ни одного раздела, то возвращаем 0 баллов.
        if ($completions->count() === 0) {
            return 0;
        }

        // Суммируем баллы всех завершенных разделов.
        // За каждый завершенный раздел студент получает 100 баллов.
        return $completions->reduce(fn ($carry, ModulePartCompletion $completion) => $carry + $completion->points(), 0);
    }

    // Функция возвращает общее количество времени, которое студент потратил на прохождение тестов.
    public function timeSpentOnTests(): int
    {
        // Получаем все завершенные разделы модулей студента.
        $completions = $this->modulePartCompletions()->get();

        // Если студент не завершил ни одного раздела, то возвращаем 0.
        if ($completions->count() === 0) {
            return 0;
        }

        // Суммируем время, которое студент потратил на прохождение тестов.
        return $completions->reduce(fn ($carry, ModulePartCompletion $completion) => $carry + $completion->timeSpentOnTest(), 0);
    }

    // Функция возвращает true, если студент является должником.
    public function isDebtor(): bool
    {
        // Получаем все разделы модулей.
        /** @var ModulePart[] $moduleParts */
        $moduleParts = ModulePart::all();

        // Получаем все завершенные разделы модулей студента.
        $completions = $this->modulePartCompletions()->get();

        // Проходим по всем разделам модулей.
        foreach ($moduleParts as $modulePart) {
            // Если раздел еще не закончился, то пропускаем его.
            if (!$modulePart->isDateOver()) {
                continue;
            }

            // Если студент не завершил раздел, то он является должником.
            /** @var ModulePartCompletion $completion */
            $completion = $completions->firstWhere('module_part_id', $modulePart->id);
            if ($completion === null || !$completion->isCompleted()) {
                return true;
            }
        }

        // Если студент завершил все разделы, то он не является должником.
        return false;
    }

    // Функция возвращает запрос, который выбирает всех администраторов.
    public function scopeAdmins($query)
    {
        return $query->where('role', static::ROLE_ADMIN);
    }

    // Функция возвращает запрос, который выбирает всех преподавателей.
    public function scopeTeachers($query)
    {
        return $query->where('role', static::ROLE_TEACHER);
    }

    // Функция возвращает запрос, который выбирает всех студентов.
    public function scopeStudents($query)
    {
        return $query->where('role', static::ROLE_STUDENT);
    }
}
