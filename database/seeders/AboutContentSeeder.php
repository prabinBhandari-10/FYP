<?php

namespace Database\Seeders;

use App\Models\AboutContent;
use Illuminate\Database\Seeder;

class AboutContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            [
                'title' => 'hero',
                'body' => 'The Lost & Found Management System is a web-based platform where students, staff, and visitors can report, search, and recover lost items in a simple and organized way. It is currently focused on college use, and it also supports nearby areas in Pokhara.',
                'sort_order' => 0,
                'is_active' => true,
                'color' => 'blue',
            ],
            [
                'title' => 'Our Mission',
                'body' => 'Our mission is to reduce stress when someone loses an item. We want to save time and make item recovery faster, easier, and more reliable.',
                'sort_order' => 1,
                'is_active' => true,
                'color' => 'green',
            ],
            [
                'title' => 'What We Provide',
                'body' => "• Easy reporting system for lost and found items\n• Search and filter system to find items quickly\n• Secure claim verification before handover\n• Admin monitoring for safe and proper use",
                'sort_order' => 2,
                'is_active' => true,
                'color' => 'purple',
            ],
            [
                'title' => 'For Our College Community',
                'body' => 'This system is built as our Final Year Project to solve real problems inside the college. It is college-oriented, and it also helps users from nearby Pokhara areas.',
                'sort_order' => 3,
                'is_active' => true,
                'color' => 'red',
            ],
            [
                'title' => 'Future Scope',
                'body' => "1. Beyond College\nIn the future, this system can expand beyond college use and support more public users.\n\n2. Across Pokhara\nIt can be scaled to serve different schools, offices, and communities across Pokhara.\n\n3. Long-Term Goal\nOur long-term goal is to make this platform useful all over Nepal.",
                'sort_order' => 4,
                'is_active' => true,
                'color' => 'gold',
            ],
        ];

        foreach ($contents as $content) {
            AboutContent::updateOrCreate(
                ['title' => $content['title']],
                $content
            );
        }
    }
}
