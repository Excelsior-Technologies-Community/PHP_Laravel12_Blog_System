<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    /**
     * Store or toggle reaction (Love, Clap, Insightful)
     */
    public function react(Request $request, Post $post)
    {
        $validated = $request->validate([
            'type' => 'required|in:love,clap,insightful',
        ]);

        $sessionId = $request->session()->getId();
        $ip = $request->ip();
        $type = $validated['type'];

        // For clap, allow multiple claps like Medium (up to 20 claps per session)
        if ($type === 'clap') {
            $existingClaps = Reaction::where('post_id', $post->id)
                ->where('type', 'clap')
                ->where('session_id', $sessionId)
                ->count();

            if ($existingClaps >= 20) {
                return response()->json([
                    'success' => true,
                    'message' => 'Maximum claps reached for this post! Thank you!',
                    'love_count' => $post->reactionCount('love'),
                    'clap_count' => $post->reactionCount('clap'),
                    'insightful_count' => $post->reactionCount('insightful'),
                    'total_reactions' => $post->totalReactionsCount(),
                ]);
            }

            Reaction::create([
                'post_id' => $post->id,
                'type' => 'clap',
                'ip_address' => $ip,
                'session_id' => $sessionId,
            ]);

            $message = 'Clapped for this post! 👏';
        } else {
            // For love & insightful: toggle behavior
            $existing = Reaction::where('post_id', $post->id)
                ->where('type', $type)
                ->where('session_id', $sessionId)
                ->first();

            if ($existing) {
                $existing->delete();
                $message = "Removed your reaction.";
            } else {
                Reaction::create([
                    'post_id' => $post->id,
                    'type' => $type,
                    'ip_address' => $ip,
                    'session_id' => $sessionId,
                ]);
                $message = $type === 'love' ? 'Loved this post! ❤️' : 'Marked as insightful! 💡';
            }
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'love_count' => $post->reactionCount('love'),
            'clap_count' => $post->reactionCount('clap'),
            'insightful_count' => $post->reactionCount('insightful'),
            'total_reactions' => $post->totalReactionsCount(),
            'user_reacted' => $post->hasUserReacted($type, $sessionId),
        ]);
    }
}
