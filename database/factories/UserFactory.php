<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(['attendee', 'organizer']),
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
            ];
        });
    }

    public function organizer()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'organizer',
            ];
        });
    }

    public function attendee()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'attendee',
            ];
        });
    }
}