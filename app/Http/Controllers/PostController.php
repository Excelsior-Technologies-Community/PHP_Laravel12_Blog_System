<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // NOTE:
    // This is a starter controller template.
    // Replace/add the remaining methods (show, edit, update, destroy, trash,
    // restore, forceDelete) from the subsequent parts.

    public function index(Request $request)
    {
        $query = Post::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        $posts = $query->latest()->paginate(4)->withQueryString();

        $statistics = [
            'total' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'draft' => Post::where('status', 'draft')->count(),
            'featured' => Post::where('is_featured', true)->count(),
            'trash' => Post::onlyTrashed()->count(),
        ];

        return view('posts.index', compact('posts', 'statistics'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $image,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
            'reading_time' => Post::calculateReadingTime($request->content),
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display Single Post
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show Edit Form
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update Post
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
        ]);

        $post->title = $request->title;
        $post->content = $request->content;

        $post->status = $request->status;

        $post->is_featured = $request->boolean('is_featured');

        $post->reading_time = Post::calculateReadingTime(
            $request->content
        );

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

            $post->image = $request->file('image')
                ->store('posts', 'public');
        }

        $post->save();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Soft Delete
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post moved to Trash.');
    }

    /**
     * Trash Page
     */
    public function trash()
    {
        $posts = Post::onlyTrashed()
            ->oldest()
            ->paginate(4);

        return view('posts.trash', compact('posts'));
    }

    /**
     * Restore Deleted Post
     */
    public function restore($id)
    {
        Post::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        return redirect()
            ->route('posts.trash')
            ->with('success', 'Post restored successfully.');
    }

    /**
     * Permanently Delete Post
     */
    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()
            ->findOrFail($id);

        if ($post->image && Storage::disk('public')->exists($post->image)) {

            Storage::disk('public')->delete($post->image);
        }

        $post->forceDelete();

        return redirect()
            ->route('posts.trash')
            ->with('success', 'Post permanently deleted.');
    }
}
