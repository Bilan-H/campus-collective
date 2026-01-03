<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    public function show(Post $post)
    {
    $post->load(['user', 'comments.user'])->loadCount('likes');
    return view('posts.show', compact('post'));
    
    $likedByMe = $post->likes()->where('users.id', auth()->id())->exists();
    return view('posts.show', compact('post', 'likedByMe'));

    }


    public function store(Request $request)
    {
        $data = $request->validate([
        'caption' => 'required|string|max:255',
        'image' => 'nullable|image|max:4096', // 4MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post = $request->user()->posts()->create([
        'caption' => $data['caption'],
        'image_path' => $imagePath,
        ]);

        // Hashtags extracted from caption: #word
        preg_match_all('/#(\w+)/', $data['caption'], $matches);
        $names = collect($matches[1])->map(fn ($t) => strtolower($t))->unique();

        $ids = $names->map(function ($name) {
            return Hashtag::firstOrCreate(
                ['slug' => $name],
                ['name' => $name, 'slug' => $name]
            )->id;
        });

        $post->hashtags()->sync($ids);

        return redirect()->route('feed.index')->with('success', 'Posted!');

    }
    public function edit(\App\Models\Post $post)
{
    abort_unless($post->user_id === auth()->id(), 403);
    return view('posts.edit', compact('post'));
}

public function update(\Illuminate\Http\Request $request, \App\Models\Post $post)
{
    abort_unless($post->user_id === auth()->id(), 403);

    $data = $request->validate([
        'caption' => ['required', 'string', 'max:1000'],
        'image'   => ['nullable', 'image', 'max:4096'],
    ]);
    
    if ($request->hasFile('image')) {
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }
        $data['image_path'] = $request->file('image')->store('posts', 'public');
    }


    $post->update($data);

    return redirect()->route('posts.show', $post)->with('success', 'Updated.');
}

public function destroy(\App\Models\Post $post)
{
    abort_unless($post->user_id === auth()->id(), 403);

    $post->delete();

    return redirect()->route('feed.index')->with('success', 'Deleted.');
}

}

