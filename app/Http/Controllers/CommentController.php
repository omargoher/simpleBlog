<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        $comments = Comment::where('post_id', $id)->get();

        return ApiResponse::success([
            'comments' => $comments
        ], 'All Comments returned successfully', 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $fields = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $post = Post::findOrFail($id);

        $comment = $post->comments()->create([
            'body' => $fields['body'],
            'user_id' => $request->user()->id,
        ]);

        return ApiResponse::success([
            'comment' => $comment
        ], 'Comment added successfully', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $fields = $request->validate([
            'body' => 'required|string',
        ]);

        $comment = $request->user()->comments()->findOrFail($id);

        $comment->update($fields);
        $comment->refresh();

        return ApiResponse::success([
            'comment' => $comment,
        ], 'Comment updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $comment = $request->user()->comments()->findOrFail($id);
        $comment->delete();

        return ApiResponse::success([], 'Post Deleted successfully', 200);
    }
}
