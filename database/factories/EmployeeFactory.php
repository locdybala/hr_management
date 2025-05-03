<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'employee_code' => 'EMP' . fake()->unique()->numberBetween(1000, 9999),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => function (array $attributes) {
                return \App\Models\User::find($attributes['user_id'])->email;
            },
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'birthday' => fake()->date(),
            'gender' => fake()->randomElement(['male', 'female']),
            'start_date' => fake()->date(),
            'end_date' => fake()->optional()->date(),
            'salary' => fake()->numberBetween(5000000, 50000000),
            'status' => fake()->randomElement(['active', 'inactive', 'on_leave']),
        ];
    }
}
