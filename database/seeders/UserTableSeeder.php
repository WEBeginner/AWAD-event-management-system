<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        
        // Organizer user
        User::create([
            'name' => 'Organizer User',
            'email' => 'organizer@example.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
        ]);
        
        // Attendee user
        User::create([
            'name' => 'Attendee User',
            'email' => 'attendee@example.com',
            'password' => Hash::make('password'),
            'role' => 'attendee',
        ]);
        
        // Create random users
        User::factory()->count(10)->create(['role' => 'organizer']);
        User::factory()->count(20)->create(['role' => 'attendee']);
    }
}
