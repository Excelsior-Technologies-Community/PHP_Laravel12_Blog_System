<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;

class BlogFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_and_many_to_many_tags_assignment_and_filtering(): void
    {
        $category = Category::create([
            'name' => 'Web Development',
            'slug' => 'web-development',
            'color' => '#7209b7',
            'icon' => 'bi-code-slash',
        ]);

        $tag1 = Tag::create(['name' => 'Laravel 12', 'slug' => 'laravel-12']);
        $tag2 = Tag::create(['name' => 'PHP 8', 'slug' => 'php-8']);

        $post = Post::create([
            'category_id' => $category->id,
            'title' => 'Mastering Laravel 12',
            'content' => 'Deep dive into Laravel 12 features.',
            'status' => 'published',
            'reading_time' => 3,
        ]);

        $post->tags()->sync([$tag1->id, $tag2->id]);

        $this->assertEquals('Web Development', $post->category->name);
        $this->assertCount(2, $post->tags);
        $this->assertNotEmpty($post->slug);

        // Filter by category
        $catResponse = $this->get(route('posts.index', ['category' => 'web-development']));
        $catResponse->assertStatus(200);
        $catResponse->assertSee('Mastering Laravel 12');

        // Filter by tag
        $tagResponse = $this->get(route('posts.index', ['tag' => 'laravel-12']));
        $tagResponse->assertStatus(200);
        $tagResponse->assertSee('Mastering Laravel 12');
    }

    public function test_nested_threaded_comments_and_replies(): void
    {
        $post = Post::create([
            'title' => 'Comments Discussion Post',
            'content' => 'This is an article that invites discussions.',
            'status' => 'published',
        ]);

        // Post top-level comment
        $commentResponse = $this->post(route('comments.store', $post->id), [
            'author_name' => 'Jane Doe',
            'author_email' => 'jane@example.com',
            'content' => 'Super insightful write-up!',
        ]);

        $commentResponse->assertSessionHas('success');
        $this->assertEquals(1, $post->comments()->count());

        $parentComment = $post->comments()->first();

        // Post nested reply
        $replyResponse = $this->post(route('comments.store', $post->id), [
            'parent_id' => $parentComment->id,
            'author_name' => 'Editor Staff',
            'author_email' => 'editor@example.com',
            'content' => 'Thanks for reading Jane!',
        ]);

        $replyResponse->assertSessionHas('success');
        $this->assertCount(1, $parentComment->replies);
        $this->assertEquals('Thanks for reading Jane!', $parentComment->replies->first()->content);
    }

    public function test_1_click_reactions_ajax_endpoint(): void
    {
        $post = Post::create([
            'title' => 'Reactions Test Article',
            'content' => 'Testing love, clap and insightful reactions.',
            'status' => 'published',
        ]);

        // Love reaction
        $loveResponse = $this->postJson(route('posts.react', $post->id), [
            'type' => 'love',
        ]);

        $loveResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'love_count' => 1,
            ]);

        // Clap reaction
        $clapResponse = $this->postJson(route('posts.react', $post->id), [
            'type' => 'clap',
        ]);

        $clapResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'clap_count' => 1,
            ]);

        $this->assertEquals(2, $post->totalReactionsCount());
    }

    public function test_slug_resolution_and_reading_time(): void
    {
        $post = Post::create([
            'title' => 'Unique Slug And Reading Time Test',
            'content' => str_repeat('Word ', 400), // ~400 words = 2 mins
            'status' => 'published',
        ]);

        $this->assertEquals('unique-slug-and-reading-time-test', $post->slug);
        $this->assertEquals(2, $post->reading_time);

        // Access via slug
        $showResponse = $this->get(route('posts.show', $post->slug));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Unique Slug And Reading Time Test');
    }

    public function test_reading_list_and_trash_routes(): void
    {
        $post = Post::create([
            'title' => 'Bookmarked Post',
            'content' => 'Saved for later.',
            'status' => 'published',
        ]);

        $readingListResponse = $this->get(route('posts.readingList', ['ids' => [$post->id]]));
        $readingListResponse->assertStatus(200);
        $readingListResponse->assertSee('Bookmarked Post');

        // Test trash
        $this->delete(route('posts.destroy', $post->id));
        $this->assertSoftDeleted('posts', ['id' => $post->id]);

        $trashResponse = $this->get(route('posts.trash'));
        $trashResponse->assertStatus(200);
        $trashResponse->assertSee('Bookmarked Post');

        // Restore
        $this->post(route('posts.restore', $post->id));
        $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
    }
}
