<x-app-layout>
    <div class="max-w-2xl mx-auto py-6 space-y-4">
        <a class="underline" href="{{ route('feed') }}">← Back to feed</a>

        <div class="border rounded p-4 space-y-2">
            <div class="text-sm text-gray-600">
                <a class="underline" href="{{ route('users.show', $post->user) }}">{{ $post->user->name }}</a>
                · {{ $post->created_at->diffForHumans() }}
            </div>

            <div class="text-lg">{{ $post->caption }}</div>

            <div class="flex flex-wrap gap-2">
                @foreach ($post->hashtags as $tag)
                    <a class="text-blue-700 underline text-sm" href="{{ route('hashtags.show', $tag) }}">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <h2 class="font-semibold">Comments</h2>

        <div class="space-y-3">
            @foreach ($post->comments as $comment)
                <div class="border rounded p-3">
                    <div class="text-sm text-gray-600">{{ $comment->user->name }}</div>
                    <div>{{ $comment->body }}</div>
                </div>
            @endforeach
        </div>

        <div class="border rounded p-4">
            <h3 class="font-semibold mb-2">Add a comment</h3>

            @if (session('comment_error'))
                <div class="p-3 bg-red-100 rounded mb-2">{{ session('comment_error') }}</div>
            @endif

            <form method="POST" action="{{ route('comments.store', $post) }}" class="space-y-2">
                @csrf
                <textarea name="body" class="w-full border rounded p-2" rows="3" required></textarea>
                @error('body') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                <button class="px-4 py-2 bg-black text-white rounded" type="submit">Comment</button>
            </form>
        </div>
    </div>
</x-app-layout>
