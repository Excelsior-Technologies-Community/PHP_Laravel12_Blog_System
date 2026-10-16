<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Reaction::truncate();
        Comment::truncate();
        DB::table('post_tag')->truncate();
        Post::truncate();
        Tag::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create Categories
        $categoriesData = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Latest breakthroughs in software, hardware, and modern tech.',
                'color' => '#4361ee',
                'icon' => 'bi-cpu',
            ],
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Modern PHP, Laravel, JavaScript, APIs, and web architecture.',
                'color' => '#7209b7',
                'icon' => 'bi-code-slash',
            ],
            [
                'name' => 'Artificial Intelligence',
                'slug' => 'artificial-intelligence',
                'description' => 'Machine learning, OpenAI embeddings, LLMs, and RAG architectures.',
                'color' => '#f72585',
                'icon' => 'bi-robot',
            ],
            [
                'name' => 'Design & UI/UX',
                'slug' => 'design-ui-ux',
                'description' => 'Crafting beautiful, accessible, and intuitive digital interfaces.',
                'color' => '#4cc9f0',
                'icon' => 'bi-palette',
            ],
            [
                'name' => 'Career & Productivity',
                'slug' => 'career-productivity',
                'description' => 'Developer career growth, pair programming, and coding habits.',
                'color' => '#2ec4b6',
                'icon' => 'bi-briefcase',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[$cData['slug']] = Category::create($cData);
        }

        // 2. Create Tags
        $tagsData = [
            'laravel12' => 'Laravel 12',
            'php' => 'PHP 8.4',
            'webdev' => 'Web Development',
            'ai' => 'AI & LLMs',
            'javascript' => 'JavaScript',
            'bootstrap5' => 'Bootstrap 5',
            'database' => 'MySQL & Eloquent',
            'tips' => 'Pro Tips',
        ];

        $tags = [];
        foreach ($tagsData as $slug => $name) {
            $tags[$slug] = Tag::create(['name' => $name, 'slug' => $slug]);
        }

        // 3. Create High-Quality Blog Posts
        $postsData = [
            [
                'category_id' => $categories['web-development']->id,
                'title' => 'Mastering Laravel 12: New Features and Eloquent Performance Tips',
                'content' => "Laravel 12 brings groundbreaking improvements to developer ergonomics and query performance.\n\n## 1. Minimalistic Application Structure\nIn Laravel 12, service providers and middleware are streamlined directly in `bootstrap/app.php`. This drastically simplifies configuration and removes redundant boilerplate.\n\n## 2. Eloquent Relationship Optimization\nWhen dealing with Many-to-Many and Polymorphic relationships, always remember to eager-load with `with()` or `loadCount()` to eliminate the infamous N+1 query dilemma.\n\n```php\n// Efficient query with relationships\n\$posts = Post::with(['category', 'tags'])\n    ->published()\n    ->latest()\n    ->paginate(10);\n```\n\n## 3. Real-Time Interactions\nWith modern reactive tooling, combining lightweight AJAX endpoints with standard Blade templates allows you to create snappy, single-page application experiences without the complexity of a heavy JavaScript build system.",
                'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1000&auto=format&fit=crop&q=80',
                'status' => 'published',
                'is_featured' => true,
                'views' => 342,
                'tag_slugs' => ['laravel12', 'php', 'webdev', 'tips'],
            ],
            [
                'category_id' => $categories['artificial-intelligence']->id,
                'title' => 'Building Semantic RAG Chatbots with OpenAI Text Embeddings',
                'content' => "Retrieval-Augmented Generation (RAG) is transforming how applications converse with proprietary knowledgebases.\n\n## Understanding Vector Similarity\nInstead of keyword matching, vector embeddings convert sentences into high-dimensional coordinates. We calculate cosine similarity between the user prompt vector and indexed document chunk vectors to find the most contextually relevant excerpts.\n\n## Chunking Strategy\nA sliding window chunking algorithm (e.g. 500 words with 50-word overlap) ensures that sentences are not abruptly severed at chunk boundaries.\n\n## Grounded GPT Responses\nBy appending retrieved source citations directly into the system prompt, LLMs generate factual, verifiable answers while eliminating hallucinations.",
                'image' => 'https://images.unsplash.com/photo-1677442136019-21780efad99a?w=1000&auto=format&fit=crop&q=80',
                'status' => 'published',
                'is_featured' => true,
                'views' => 520,
                'tag_slugs' => ['ai', 'webdev', 'tips'],
            ],
            [
                'category_id' => $categories['design-ui-ux']->id,
                'title' => 'Designing Dark Mode for High-Readability Editorial Blogs',
                'content' => "Dark mode is not simply inverting white to black; it requires nuanced contrast calibration.\n\n## The Problem with Pure Black\nUsing `#000000` backgrounds produces harsh contrast that causes visual halation and eye fatigue. Instead, use rich dark tones such as `#121826` or `#0f172a`.\n\n## Typography Hierarchy\nMaintain clear differentiation between primary headings, secondary meta tags, and body text by employing varying opacity and muted tints.\n\n## Preserving State\nUsing `localStorage` enables instant theme restoration on page load, eliminating unsightly theme flashes during navigation.",
                'image' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?w=1000&auto=format&fit=crop&q=80',
                'status' => 'published',
                'is_featured' => false,
                'views' => 280,
                'tag_slugs' => ['bootstrap5', 'webdev', 'tips'],
            ],
            [
                'category_id' => $categories['career-productivity']->id,
                'title' => '10 Habits of Highly Productive Full-Stack Developers',
                'content' => "Becoming an exceptional developer requires intentional workflow habits rather than sheer hours at the desk.\n\n## 1. Master Automated Testing\nWriting PHPUnit and Pest feature tests saves hundreds of manual verification hours across the project lifecycle.\n\n## 2. Leverage Git Effectively\nMake atomic, well-documented commits. Never batch unrelated features into one messy pull request.\n\n## 3. Protect Deep Work Intervals\nBlock dedicated 90-minute morning focus sessions without email or slack notifications to solve high-complexity architectural challenges.",
                'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1000&auto=format&fit=crop&q=80',
                'status' => 'published',
                'is_featured' => false,
                'views' => 195,
                'tag_slugs' => ['productivity', 'tips'],
            ],
            [
                'category_id' => $categories['technology']->id,
                'title' => 'The Evolution of Cloud Microservices and Serverless PHP',
                'content' => "PHP has rapidly matured to run seamlessly in serverless cloud environments via Laravel Vapor and AWS Lambda.\n\n## Instant Scalability\nServerless architecture handles traffic spikes effortlessly by provisioning workers on-demand within milliseconds.\n\n## Cost Optimization\nYou only pay for execution milliseconds during active requests, eliminating idle server expenses during low-traffic periods.",
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1000&auto=format&fit=crop&q=80',
                'status' => 'published',
                'is_featured' => false,
                'views' => 150,
                'tag_slugs' => ['webdev', 'php'],
            ],
        ];

        foreach ($postsData as $pData) {
            $tagSlugs = $pData['tag_slugs'];
            unset($pData['tag_slugs']);

            $pData['reading_time'] = Post::calculateReadingTime($pData['content']);
            $post = Post::create($pData);

            // Attach Tags
            $attachIds = [];
            foreach ($tagSlugs as $ts) {
                if (isset($tags[$ts])) {
                    $attachIds[] = $tags[$ts]->id;
                }
            }
            $post->tags()->sync($attachIds);

            // Add Reactions
            for ($i = 0; $i < rand(8, 25); $i++) {
                Reaction::create([
                    'post_id' => $post->id,
                    'type' => 'love',
                    'session_id' => 'seed_' . uniqid(),
                ]);
            }
            for ($i = 0; $i < rand(5, 18); $i++) {
                Reaction::create([
                    'post_id' => $post->id,
                    'type' => 'clap',
                    'session_id' => 'seed_' . uniqid(),
                ]);
            }
            for ($i = 0; $i < rand(3, 12); $i++) {
                Reaction::create([
                    'post_id' => $post->id,
                    'type' => 'insightful',
                    'session_id' => 'seed_' . uniqid(),
                ]);
            }

            // Add Nested Comments
            $comment1 = Comment::create([
                'post_id' => $post->id,
                'author_name' => 'Michael Scott',
                'author_email' => 'michael@example.com',
                'content' => 'Brilliant breakdown! The Eloquent optimization tips helped our team cut response latency significantly.',
                'status' => 'approved',
                'is_admin' => false,
                'created_at' => now()->subHours(8),
            ]);

            // Reply to comment 1
            Comment::create([
                'post_id' => $post->id,
                'parent_id' => $comment1->id,
                'author_name' => 'Editor in Chief',
                'author_email' => 'editor@blogsphere.com',
                'content' => 'Thank you Michael! Glad you found the relationship loading advice helpful.',
                'status' => 'approved',
                'is_admin' => true,
                'created_at' => now()->subHours(5),
            ]);

            Comment::create([
                'post_id' => $post->id,
                'author_name' => 'Sarah Connor',
                'author_email' => 'sarah@example.com',
                'content' => 'Clean explanations and modern formatting. Looking forward to your next write-up!',
                'status' => 'approved',
                'is_admin' => false,
                'created_at' => now()->subHours(2),
            ]);
        }
    }
}