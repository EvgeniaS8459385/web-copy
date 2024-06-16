<?php

namespace Database\Factories\Module;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\Module\ModulePartQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ModulePartQuestionFactory extends Factory
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

    public function forModulePart(ModulePart $part): ModulePartQuestionFactory
    {
        return $this->state(fn() => ['module_part_id' => $part->id]);
    }

    public function withOrder(int $order): ModulePartQuestionFactory
    {
        return $this->state(fn() => ['order' => $order]);
    }

    public function withType(string $type): ModulePartQuestionFactory
    {
        return $this->state(fn() => ['type' => $type]);
    }
}
