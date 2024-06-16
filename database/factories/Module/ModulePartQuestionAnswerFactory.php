<?php

namespace Database\Factories\Module;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\Module\ModulePartQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ModulePartQuestionAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => fake()->paragraph(),
        ];
    }

    public function forQuestion(ModulePartQuestion $question): ModulePartQuestionAnswerFactory
    {
        return $this->state(fn() => ['module_part_question_id' => $question->id]);
    }

    public function withOrder(int $order): ModulePartQuestionAnswerFactory
    {
        return $this->state(fn() => ['order' => $order]);
    }

    public function withCorrect(bool $correct): ModulePartQuestionAnswerFactory
    {
        return $this->state(fn() => ['is_correct' => $correct]);
    }
}
