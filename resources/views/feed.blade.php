<!doctype html>
<html>
<head><meta charset="utf-8"><title>Feed</title></head>
<body>
    <div class="text-sm text-gray-600">
    Logged in as <span class="font-semibold">{{ auth()->user()->name }}</span>
</div>

    <div class="max-w-2xl mx-auto py-6 space-y-6">
        <h1>Campus Collective</h1>

        @if (session('success'))
            <div>{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('posts.store') }}">
            @csrf
            <textarea name="caption" rows="3" required>{{ old('caption') }}</textarea>
            @error('caption') <div>{{ $message }}</div> @enderror
            <button type="submit">Post</button>
        </form>
             
        <div>
            @forelse ($posts as $post)
                <div>
                    <div>
                        <span>{{ $post->user->name }}</span>
                        · {{ $post->created_at->diffForHumans() }}
                    </div>

                    <div>{{ $post->caption }}</div>

                    <div>
                        @foreach ($post->hashtags as $tag)
                            <span>#{{ $tag->name }}</span>
                        @endforeach
                    </div>

                    <div>
                        <a href="{{ route('posts.show', $post) }}">
                            View ({{ $post->comments->count() }} comments)
                
                        </a>
                    </div>
                </div>
            @empty
                <p>No posts yet.</p>
            @endforelse
        </div>
    </div>
</body>
</html>

