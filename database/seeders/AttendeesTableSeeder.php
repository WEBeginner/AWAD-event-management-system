<?php

namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendeesTableSeeder extends Seeder
{
    public function run()
    {
        $events = Event::where('is_approved', true)->get();
        $attendees = User::where('role', 'attendee')->get();

        foreach ($events as $event) {
            // Skip past events
            if ($event->end_time < now()) {
                continue;
            }

            $randomAttendees = $attendees->random(rand(0, min(15, $attendees->count())));

            foreach ($randomAttendees as $attendee) {
                // Don't let attendees register for their own events
                if ($attendee->id === $event->user_id) {
                    continue;
                }

                Attendee::create([
                    'user_id' => $attendee->id,
                    'event_id' => $event->id,
                    'status' => $this->getRandomStatus($event),
                    'ticket_count' => rand(1, 3),
                    'notes' => rand(0, 1) ? 'Special requirements: ' . fake()->sentence() : null,
                ]);
            }
        }
    }

    private function getRandomStatus($event)
    {
        $statuses = ['registered', 'attended', 'cancelled'];
        
        if ($event->start_time > now()) {
            return 'registered';
        }
        
        return $statuses[array_rand($statuses)];
    }
}