<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display Blog Dashboard / Post Listing
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'tags', 'reactions'])->withCount('comments');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category Filter (ID or Slug)
        if ($request->filled('category')) {
            $categoryParam = $request->category;
            $query->whereHas('category', function ($q) use ($categoryParam) {
                $q->where('id', $categoryParam)->orWhere('slug', $categoryParam);
            });
        }

        // Tag Filter (ID or Slug)
        if ($request->filled('tag')) {
            $tagParam = $request->tag;
            $query->whereHas('tags', function ($q) use ($tagParam) {
                $q->where('tags.id', $tagParam)->orWhere('tags.slug', $tagParam);
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Featured Filter
        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        // Sorting
        if ($request->sort === 'trending') {
            $query->trending();
        } elseif ($request->sort === 'popular') {
            $query->orderByDesc('views');
        } elseif ($request->sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $posts = $query->paginate(6)->withQueryString();

        // Sidebar Data
        $categories = Category::withCount(['publishedPosts'])->orderBy('name')->get();
        $popularTags = Tag::withCount('posts')->orderByDesc('posts_count')->take(12)->get();
        
        $trendingPosts = Post::published()
            ->with(['category'])
            ->withCount(['reactions', 'comments'])
            ->orderByRaw('(views + (reactions_count * 5) + (comments_count * 3)) DESC')
            ->take(5)
            ->get();

        $statistics = [
            'total' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'draft' => Post::where('status', 'draft')->count(),
            'featured' => Post::where('is_featured', true)->count(),
            'trash' => Post::onlyTrashed()->count(),
            'categories' => Category::count(),
            'tags' => Tag::count(),
        ];

        // Category Analytics (Percentage and Views)
        $totalPublished = max(1, $statistics['published']);
        $categoryAnalytics = $categories->map(function ($cat) use ($totalPublished) {
            $count = $cat->published_posts_count;
            return [
                'name' => $cat->name,
                'slug' => $cat->slug,
                'color' => $cat->color,
                'icon' => $cat->icon,
                'count' => $count,
                'percentage' => round(($count / $totalPublished) * 100, 1),
            ];
        });

        return view('posts.index', compact(
            'posts',
            'statistics',
            'categories',
            'popularTags',
            'trendingPosts',
            'categoryAnalytics'
        ));
    }

    /**
     * Show Create Form
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('posts.create', compact('categories', 'tags'));
    }

    /**
     * Store Post
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Post::generateUniqueSlug($request->title),
            'content' => $request->content,
            'image' => $image,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
            'reading_time' => Post::calculateReadingTime($request->content),
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully with categories and tags!');
    }

    /**
     * Display Single Post (Supports Slug or ID)
     */
    public function show($identifier)
    {
        // Find by slug or ID
        $post = Post::with(['category', 'tags', 'reactions', 'comments.replies'])
            ->where('slug', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        // Increment view count
        $post->increment('views');
        $post->refresh();

        // Related Posts (Same category or sharing tags)
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, function ($q) use ($post) {
                $q->where('category_id', $post->category_id);
            })
            ->latest()
            ->take(3)
            ->get();

        // Trending posts for sidebar
        $trendingPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->trending()
            ->take(4)
            ->get();

        $categories = Category::withCount('publishedPosts')->get();
        $popularTags = Tag::withCount('posts')->take(10)->get();

        return view('posts.show', compact(
            'post',
            'relatedPosts',
            'trendingPosts',
            'categories',
            'popularTags'
        ));
    }

    /**
     * Show Edit Form
     */
    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $selectedTags = $post->tags->pluck('id')->toArray();

        return view('posts.edit', compact('post', 'categories', 'tags', 'selectedTags'));
    }

    /**
     * Update Post
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post->title = $request->title;
        $post->category_id = $request->category_id;
        $post->content = $request->content;
        $post->status = $request->status;
        $post->is_featured = $request->boolean('is_featured');
        $post->reading_time = Post::calculateReadingTime($request->content);

        if ($request->boolean('remove_image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $post->image = null;
        }

        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $post->image = $request->file('image')->store('posts', 'public');
        }

        $post->save();

        // Sync Tags
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Soft Delete
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post moved to Trash.');
    }

    /**
     * Trash Page
     */
    public function trash()
    {
        $posts = Post::onlyTrashed()
            ->with(['category'])
            ->oldest()
            ->paginate(6);

        return view('posts.trash', compact('posts'));
    }

    /**
     * Restore Deleted Post
     */
    public function restore($id)
    {
        Post::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('posts.trash')
            ->with('success', 'Post restored successfully.');
    }

    /**
     * Permanently Delete Post
     */
    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->tags()->detach();
        $post->forceDelete();

        return redirect()->route('posts.trash')
            ->with('success', 'Post permanently deleted.');
    }

    /**
     * Bookmarks / Saved Reading List
     */
    public function readingList(Request $request)
    {
        $postIds = $request->input('ids');
        if (is_string($postIds)) {
            $postIds = explode(',', $postIds);
        }

        $posts = Post::published()
            ->with(['category', 'tags'])
            ->when(!empty($postIds), function ($q) use ($postIds) {
                $q->whereIn('id', (array)$postIds);
            }, function ($q) {
                // If no IDs provided, return empty
                $q->whereRaw('1 = 0');
            })
            ->latest()
            ->get();

        return view('posts.reading-list', compact('posts'));
    }

    /**
     * Create Sample Data (Reset and Seed)
     */
    public function createSampleData()
    {
        $seeder = new \Database\Seeders\DatabaseSeeder();
        $seeder->run();

        return redirect()->route('posts.index')
            ->with('success', 'Sample blog data with categories, tags, reactions, and threaded comments re-seeded successfully!');
    }
}
