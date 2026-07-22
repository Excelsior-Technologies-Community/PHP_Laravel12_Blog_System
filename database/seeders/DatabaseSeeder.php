<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Post::create([
            'title' => 'First Blog Post',
            'content' => 'Welcome to our Laravel blog system! This is our first post. Here you can explore articles, share knowledge, and learn Laravel development through practical examples.',
            'image' => 'https://picsum.photos/id/180/1200/700',
            'status' => 'published',
            'is_featured' => true,
            'reading_time' => Post::calculateReadingTime(
                'Welcome to our Laravel blog system! This is our first post. Here you can explore articles, share knowledge, and learn Laravel development through practical examples.'
            ),
        ]);

        Post::create([
            'title' => 'Laravel Tips',
            'content' => 'Here are some useful Laravel tips and tricks for beginners. Learn about routing, controllers, Blade templates, Eloquent ORM, and best practices.',
            'image' => 'https://picsum.photos/id/20/1200/700',
            'status' => 'published',
            'is_featured' => false,
            'reading_time' => Post::calculateReadingTime(
                'Here are some useful Laravel tips and tricks for beginners. Learn about routing, controllers, Blade templates, Eloquent ORM, and best practices.'
            ),
        ]);

        Post::create([
            'title' => 'PHP Development',
            'content' => 'PHP continues to be one of the most popular web development languages. Combined with Laravel, it provides a powerful framework.',
            'image' => 'https://picsum.photos/id/48/1200/700',
            'status' => 'draft',
            'is_featured' => false,
            'reading_time' => Post::calculateReadingTime(
                'PHP continues to be one of the most popular web development languages. Combined with Laravel, it provides a powerful framework.'
            ),
        ]);
    }
}