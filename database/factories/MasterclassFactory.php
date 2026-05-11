<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Masterclass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasterclassFactory extends Factory
{
    protected $model = Masterclass::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'instructor_id' => User::factory()->create(['role' => 'instructor'])->id,
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'date' => fake()->dateTimeBetween('now', '+1 month'),
            'time' => fake()->time('H:i'),
            'max_participants' => fake()->numberBetween(5, 30),
            'price' => fake()->randomFloat(2, 10, 500),
        ];
    }
}
