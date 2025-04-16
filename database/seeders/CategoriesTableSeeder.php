<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Technology', 'Business', 'Art & Culture', 
            'Music', 'Food & Drink', 'Health & Wellness',
            'Sports & Fitness', 'Education', 'Science', 'Travel'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'description' => $this->getCategoryDescription($category),
            ]);
        }
    }

    private function getCategoryDescription($category)
    {
        $descriptions = [
            'Technology' => 'Events about the latest tech trends and innovations',
            'Business' => 'Networking and professional development events',
            'Art & Culture' => 'Exhibitions, performances and cultural experiences',
            'Music' => 'Concerts, festivals and music-related gatherings',
            'Food & Drink' => 'Culinary events, tastings and cooking classes',
            'Health & Wellness' => 'Fitness classes, wellness workshops and health seminars',
            'Sports & Fitness' => 'Sporting events, tournaments and fitness activities',
            'Education' => 'Workshops, lectures and learning opportunities',
            'Science' => 'Science fairs, lectures and experimental demonstrations',
            'Travel' => 'Travel expos, adventure trips and destination showcases'
        ];

        return $descriptions[$category] ?? 'Events related to '.$category;
    }
}