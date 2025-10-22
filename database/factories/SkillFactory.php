<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Skill;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        return [
            'skill_name' => $this->faker->unique()->word,
            'type' => $this->faker->randomElement(['technical', 'soft']),
        ];
    }
}
