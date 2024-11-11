<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        return Post::with('category')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'summary' => 'required',
            'postDate' => 'required|date',
            'content' => 'required',
            'image' => 'nullable|image',
            'category_id' => 'required|exists:categories,id',
        ]);

        $path = $request->file('image') ? $request->file('image')->store('images') : null;

        $post = Post::create([
            'title' => $request->title,
            'summary' => $request->summary,
            'postDate' => $request->postDate,
            'content' => $request->content,
            'imageUrl' => $path,
            'category_id' => $request->category_id,
        ]);

        return $post;
    }

    public function show(Post $post)
    {
        return $post->load('category');
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required',
            'summary' => 'required',
            'postDate' => 'required|date',
            'content' => 'required',
            'image' => 'nullable|image',
            'category_id' => 'required|exists:categories,id',
        ]);

        $path = $request->file('image') ? $request->file('image')->store('images') : $post->imageUrl;

        $post->update([
            'title' => $request->title,
            'summary' => $request->summary,
            'postDate' => $request->postDate,
            'content' => $request->content,
            'imageUrl' => $path,
            'category_id' => $request->category_id,
        ]);

        return $post;
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }
}
