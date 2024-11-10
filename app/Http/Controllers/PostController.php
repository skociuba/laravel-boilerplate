<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'status' => true,
            'message' => 'Posts retrieved successfully',
            'data' => $posts
        ], 200);
    }

    public function show($id)
    {
        $post =  Post::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Post found successfully',
            'data' => $post
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|unique:posts|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255|unique:posts,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully',
            'data' => $post
        ], 200);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully'
        ], 204);
    }
}
