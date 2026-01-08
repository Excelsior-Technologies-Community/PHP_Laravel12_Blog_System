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
            'content' => 'Welcome to our Laravel blog system! This is our first post.'
        ]);

        Post::create([
            'title' => 'Laravel Tips',
            'content' => 'Here are some useful Laravel tips and tricks for beginners.'
        ]);

        Post::create([
            'title' => 'PHP Development',
            'content' => 'PHP continues to be one of the most popular web development languages.'
        ]);
    }
}