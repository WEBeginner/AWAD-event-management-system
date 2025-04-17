<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+3 months');
        $endDate = $this->faker->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s').' +2 days');
        
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->paragraphs(3, true),
            'start_time' => $startDate,
            'end_time' => $endDate,
            'location' => $this->faker->address,
            'capacity' => $this->faker->numberBetween(10, 100),
            'price' => $this->faker->randomElement([0, 0, 0, 0, 10, 20, 50]),
            'is_approved' => $this->faker->boolean(80),
        ];
    }
    
    public function past()
    {
        return $this->state(function (array $attributes) {
            $startDate = $this->faker->dateTimeBetween('-6 months', '-1 day');
            $endDate = $this->faker->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s').' +2 days');
            
            return [
                'start_time' => $startDate,
                'end_time' => $endDate,
            ];
        });
    }
    
    public function requiresApproval()
    {
        return $this->state(function (array $attributes) {
            return [
                'requires_approval' => true,
            ];
        });
    }
    
    public function unapproved()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => false,
            ];
        });
    }
    
    public function free()
    {
        return $this->state(function (array $attributes) {
            return [
                'price' => 0,
            ];
        });
    }
    
    public function paid()
    {
        return $this->state(function (array $attributes) {
            return [
                'price' => $this->faker->randomElement([10, 20, 50, 100]),
            ];
        });
    }
}