<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    public function run()
    {
        $organizers = User::where('role', 'organizer')->get();
        $categories = Category::all();

        // Create upcoming events
        foreach ($organizers as $organizer) {
            Event::factory()
                ->count(rand(2, 5))
                ->create([
                    'user_id' => $organizer->id,
                    'is_approved' => true,
                    'start_time' => Carbon::now()->addDays(rand(1, 30)),
                    'end_time' => Carbon::now()->addDays(rand(1, 30))->addHours(rand(2, 8)),
                ])
                ->each(function ($event) use ($categories) {
                    $event->categories()->attach(
                        $categories->random(rand(1, 3))->pluck('id')
                    );
                });
        }

        // Create past events
        Event::factory()
            ->count(10)
            ->create([
                'is_approved' => true,
                'start_time' => Carbon::now()->subDays(rand(1, 60)),
                'end_time' => Carbon::now()->subDays(rand(1, 60))->addHours(rand(2, 8)),
            ])
            ->each(function ($event) use ($categories) {
                $event->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')
                );
            });

        // Create events needing approval
        Event::factory()
            ->count(5)
            ->create([
                'is_approved' => false,
                'start_time' => Carbon::now()->addDays(rand(1, 30)),
                'end_time' => Carbon::now()->addDays(rand(1, 30))->addHours(rand(2, 8)),
            ])
            ->each(function ($event) use ($categories) {
                $event->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')
                );
            });
    }
}