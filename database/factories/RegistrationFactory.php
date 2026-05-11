<?php

namespace Database\Factories;

use App\Models\Masterclass;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'masterclass_id' => Masterclass::factory(),
        ];
    }
}
