<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store new comment or nested reply
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:100',
            'author_email' => 'required|email|max:150',
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->allComments()->create([
            'parent_id' => $validated['parent_id'] ?? null,
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'content' => $validated['content'],
            'status' => 'approved',
            'is_admin' => str_contains(strtolower($validated['author_email']), 'admin') || str_contains(strtolower($validated['author_email']), 'editor'),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your comment has been posted successfully!',
                'comment' => [
                    'id' => $comment->id,
                    'parent_id' => $comment->parent_id,
                    'author_name' => $comment->author_name,
                    'content' => e($comment->content),
                    'is_admin' => $comment->is_admin,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'avatar' => $comment->avatarUrl(60),
                ]
            ]);
        }

        return back()->with('success', 'Your comment has been published!');
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Comment removed successfully.');
    }

    /**
     * Toggle approval status for moderation
     */
    public function toggleStatus(Comment $comment)
    {
        $newStatus = $comment->status === 'approved' ? 'pending' : 'approved';
        $comment->update(['status' => $newStatus]);

        return back()->with('success', "Comment marked as {$newStatus}.");
    }
}
