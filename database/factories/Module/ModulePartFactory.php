<?php

namespace Database\Factories\Module;

use App\Models\Module\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ModulePartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'content' => fake()->paragraph(),
        ];
    }

    public function forModule(Module $module): static
    {
        return $this->state(fn() => ['module_id' => $module->id]);
    }

    public function withOrder(int $order): static
    {
        return $this->state(fn() => ['order' => $order]);
    }
}
