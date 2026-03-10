<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name'        => fake()->firstName(),
            'middle_name'       => null,
            'last_name'         => fake()->lastName(),
            'suffix'            => null,
            'username'          => fake()->unique()->userName(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
            'sex'               => fake()->randomElement(['male', 'female']),
            'unit'              => fake()->randomElement([
                                    'BDCU', 'CUI', 'COMMAND', 'ISU', 
                                    'LSO', 'PAU', 'PG1', 'PG3', 
                                    'PG4', 'PG10', 'PPBU'
                                  ]),
            'type'              => 'user',
            'is_active'         => true,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}