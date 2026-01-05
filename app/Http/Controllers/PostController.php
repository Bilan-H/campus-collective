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
        // load relationships and counts
        $post->load(['user', 'comments.user'])
             ->loadCount('likes');

        // if user has liked the post
        $likedByMe = $post->likes()
            ->where('users.id', auth()->id())
            ->exists();

        return view('posts.show', compact('post', 'likedByMe'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caption' => ['required', 'string', 'max:255'],
            'image'   => ['nullable', 'image', 'max:4096'], // 4MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post = $request->user()->posts()->create([
            'caption'    => $data['caption'],
            'image_path' => $imagePath,
        ]);

        // Hashtags 
        preg_match_all('/#(\w+)/', $data['caption'], $matches);
        $names = collect($matches[1])
            ->map(fn ($t) => strtolower($t))
            ->unique();

        $ids = $names->map(function ($name) {
            return Hashtag::firstOrCreate(
                ['slug' => $name],
                ['name' => $name, 'slug' => $name]
            )->id;
        });

        $post->hashtags()->sync($ids);

        return redirect()->route('feed.index')->with('success', 'Posted!');
    }

    public function edit(Post $post)
    {
        // authorisationad: admin or owner
        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validate([
            'caption' => ['required', 'string', 'max:1000'],
            'image'   => ['nullable', 'image', 'max:4096'],
        ]);

        $update = [
            'caption' => $data['caption'],
        ];

        if ($request->hasFile('image')) {
            // deleting old image if exists
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }

            $update['image_path'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($update);

        return redirect()->route('posts.show', $post)->with('success', 'Updated.');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Delete image file too
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return redirect()->route('feed.index')->with('success', 'Deleted.');
    }
}

 

