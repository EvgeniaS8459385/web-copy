<?php

namespace Database\Seeders;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\Module\ModulePartQuestion;
use App\Models\Module\ModulePartQuestionAnswer;
use App\Models\StudentGroup\StudentGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::count() <= 0) {
            User::factory()->admin()->create([
                'name' => 'Test Admin User',
                'email' => 'admin@example.com',
                'password' => 'admin'
            ]);

            $teacher1 = User::factory()->teacher()->create([
                'name' => 'Test Teacher User',
                'email' => 'teacher@example.com',
                'password' => 'teacher'
            ]);

            $teacher2 = User::factory()->teacher()->create([
                'name' => 'Test Teacher User 2',
                'email' => 'teacher2@example.com',
                'password' => 'teacher2'
            ]);

            $group1 = StudentGroup::factory()->create([
                'name' => 'Test Student Group 1',
            ]);
            $group2 = StudentGroup::factory()->create([
                'name' => 'Test Student Group 2',
            ]);

            User::factory()->student($group1, $teacher1)->create([
                'name' => 'Test Student User',
                'email' => 'student@example.com',
                'password' => 'student'
            ]);

            User::factory()->student($group2, $teacher2)->create([
                'name' => 'Test Student User 2',
                'email' => 'student2@example.com',
                'password' => 'student2'
            ]);
        }

        if (Module::count() <= 0) {
            $cssModule = Module::factory()->create([
                'name' => 'CSS3-анимация',
                'description' => 'Этот блок по CSS является логическим продолжением блоков по HTML.',
            ]);

            $cssModulePart1 = ModulePart::factory()->forModule($cssModule)->create([
                'name' => 'Ключевые кадры',
                'content' => 'CSS3-анимация придаёт сайтам динамичность. Она оживляет веб-страницы, улучшая взаимодействие с пользователем. В отличие от CSS3-переходов, создание анимации базируется на ключевых кадрах, которые позволяют автоматически воспроизводить и повторять эффекты на протяжении заданного времени, а также останавливать анимацию внутри цикла.

CSS3-анимация может применяться практически для всех html-элементов, а также для псевдоэлементов :before и :after. Список анимируемых свойств приведен на этой странице. При создании анимации не стоит забывать о возможных проблемах с производительностью, так как на изменение некоторых свойств требуется много ресурсов.',
                'order' => 1,
                'time_limit' => 60 * 5,
                'date_limit' => now()->addDays(300),
            ]);

            $cssModulePart2 = ModulePart::factory()->forModule($cssModule)->create([
                'name' => 'Синтаксис',
                'content' => 'Синтаксис CSS состоит из селекторов и объявлений. Селекторы — это паттерны, используемые для выбора элементов, которые вы хотите стилизовать. Объявления содержат пары имя-значение, разделенные двоеточием, и заканчиваются точкой с запятой.',
                'order' => 2,
                'time_limit' => 60 * 5,
                'date_limit' => now()->addDays(300),
            ]);

            $question1 = ModulePartQuestion::factory()
                ->forModulePart($cssModulePart1)
                ->withType(ModulePartQuestion::TYPE_SINGLE_CHOICE)
                ->create([
                    'text' => 'Что такое CSS?',
                    'order' => 1,
                ]);

            ModulePartQuestionAnswer::factory()
                ->forQuestion($question1)
                ->withCorrect(true)
                ->create([
                    'text' => 'Язык таблиц стилей',
                    'order' => 1,
                ]);

            ModulePartQuestionAnswer::factory()
                ->forQuestion($question1)
                ->withCorrect(false)
                ->create([
                    'text' => 'Язык программирования',
                    'order' => 2,
                ]);

            $question2 = ModulePartQuestion::factory()
                ->forModulePart($cssModulePart2)
                ->withType(ModulePartQuestion::TYPE_SINGLE_CHOICE)
                ->create([
                    'text' => 'Что включает в себя синтаксис CSS?',
                    'order' => 2,
                ]);

            ModulePartQuestionAnswer::factory()
                ->forQuestion($question2)
                ->withCorrect(true)
                ->create([
                    'text' => 'Селекторы и объявления',
                    'order' => 1,
                ]);

            ModulePartQuestionAnswer::factory()
                ->forQuestion($question2)
                ->withCorrect(false)
                ->create([
                    'text' => 'Теги и атрибуты',
                    'order' => 2,
                ]);
        }
    }
}
