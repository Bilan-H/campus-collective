<x-app-layout>
    <div class="max-w-2xl mx-auto py-6 space-y-4">
        <a class="underline" href="{{ route('feed') }}">← Back to feed</a>

        <div class="border rounded p-4 flex items-center justify-between">
            <div>
                <div class="text-xl font-bold">{{ $user->name }}</div>
                <div class="text-sm text-gray-600">{{ $user->email }}</div>
            </div>

            @if (! $isMe)
                @if ($isFollowing)
                    <form method="POST" action="{{ route('follow.destroy', $user) }}">
                        @csrf @method('DELETE')
                        <button class="px-4 py-2 bg-gray-200 rounded" type="submit">Unfollow</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('follow.store', $user) }}">
                        @csrf
                        <button class="px-4 py-2 bg-black text-white rounded" type="submit">Follow</button>
                    </form>
                @endif
            @endif
        </div>

        <h2 class="font-semibold">Posts</h2>
        <div class="space-y-3">
            @foreach ($user->posts as $post)
                <div class="border rounded p-3">
                    <a class="underline" href="{{ route('posts.show', $post) }}">{{ $post->caption }}</a>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
