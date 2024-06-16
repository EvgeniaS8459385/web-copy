<?php

namespace App\Models\News;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель новости.
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $picture
 * @property Carbon $created_at
 */
class Article extends Model
{
    // Поля, которые можно заполнять через метод create или fill.
    protected $fillable = [
        'title',
        'content',
        'picture'
    ];

    // Таблица, в которой хранятся данные модели.
    protected $table = 'news_articles';

    // Получить короткое содержание новости.
    public function cutContent(int $len): string {
        // Удалить все html теги из содержания новости.
        $content = strip_tags($this->content);
        // Обрезать содержание новости до $len символов.
        $content = substr($content, 0, $len);
        // Добавить многоточие в конец.
        $content = $content . '…';

        return $content;
    }
}
