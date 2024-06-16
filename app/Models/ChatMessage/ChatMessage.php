<?php

namespace App\Models\ChatMessage;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель сообщения чата между пользователями.
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $message
 * @property Carbon $created_at
 * @property User $sender
 * @property User $receiver
 * @property bool $is_read
 */
class ChatMessage extends Model
{
    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    // Преобразование типов полей модели.
    protected function casts()
    {
        return [
            // Поле message будет зашифровано при сохранении в БД и дешифровано при получении из БД.
            'message' => 'encrypted',
        ];
    }

    // Отношение многие к одному. Один пользователь может отправить много сообщений.
    public function sender(): BelongsTo
    {
        // Возвращает связь с моделью User, где sender_id - внешний ключ.
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Отношение многие к одному. Один пользователь может получить много сообщений.
    public function receiver(): BelongsTo
    {
        // Возвращает связь с моделью User, где receiver_id - внешний ключ.
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Отметить сообщение как прочитанное.
    public function markAsRead()
    {
        $this->is_read = true;
        // Сохранить изменения в БД.
        $this->save();
    }
}
