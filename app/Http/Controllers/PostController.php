<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * search with ?search=
     * ?page=1
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $posts = Post::with('user');

        if ($search) {
            $posts->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
        }

        $posts = $posts->orderBy('created_at', 'desc')->paginate(10);

        return ApiResponse::success([
            'posts' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'last_page' => $posts->lastPage(),
            ]
        ], "Posts retrived succesfully", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title'=> 'required|string',
            'body' => 'required|string',
        ]);

        $post = $request->user()->posts()->create($fields);

        return ApiResponse::success([
            'post' => $post,
        ], 'Post Create successfully', 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('user')
            ->withCount('comments')
            ->findOrFail($id);

        return ApiResponse::success([
            'posts' => $post,
        ], "Post retrived succesfully", 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $fields = $request->validate([
            'title'=> 'sometimes|required|string',
            'body' => 'sometimes|required|string',
        ]);

        $post = $request->user()->posts()->findOrFail($id);

        $post->update($fields);
        $post->refresh();

        return ApiResponse::success([
            'post' => $post,
        ], 'Post updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $post = $request->user()->posts()->findOrFail($id);
        $post->delete();

        return ApiResponse::success([], 'Post Deleted successfully', 200);
    }
}
