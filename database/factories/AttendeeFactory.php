<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AttendeeFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'event_id' => \App\Models\Event::factory(),
            'status' => $this->faker->randomElement(['registered', 'pending', 'approved', 'rejected', 'attended', 'cancelled']),
            'ticket_count' => $this->faker->numberBetween(1, 5),
            'notes' => $this->faker->boolean(30) ? $this->faker->sentence : null,
        ];
    }
    
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }
    
    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
            ];
        });
    }
    
    public function registered()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'registered',
            ];
        });
    }
    
    public function attended()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'attended',
            ];
        });
    }
    
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
            ];
        });
    }
}