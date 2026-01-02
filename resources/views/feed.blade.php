<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Feed — Campus Collective</title>
</head>

<body style="margin:0;font-family:system-ui;background:#fafafa;">
<div style="max-width:900px;margin:0 auto;padding:22px 18px;">

    <!-- Top bar -->
    <div style="display:flex;align-items:center;justify-content:center;position:relative;margin-bottom:20px;">
        <div style="position:absolute;left:0;color:#f97316;font-weight:900;">
            FEED
        </div>

        <div style="text-align:center;">
            <div style="color:#f97316;font-weight:900;font-size:28px;">
                CAMPUS COLLECTIVE ;)
            </div>

            <div style="font-size:13px;color:#666;margin-top:6px;">
                Logged in as <strong>{{ auth()->user()->name }}</strong>
                · <a href="{{ route('profile.edit') }}" style="color:#f97316;font-weight:800;">Profile</a>
                ·
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit"
                        style="border:none;background:none;color:#f97316;font-weight:800;cursor:pointer;">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Create post -->
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;margin-bottom:18px;">
        @if (session('success'))
            <div style="background:#ecfdf5;border:1px solid #bbf7d0;padding:10px;border-radius:10px;margin-bottom:10px;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('posts.store') }}">
            @csrf

            <label style="font-weight:800;">Share something</label>

            <textarea name="caption" rows="3" required
                style="width:100%;margin-top:8px;border:1px solid #d1d5db;border-radius:10px;padding:10px;">{{ old('caption') }}</textarea>

            @error('caption')
                <div style="color:#b91c1c;font-size:13px;margin-top:6px;">{{ $message }}</div>
            @enderror

            <div style="text-align:right;margin-top:10px;">
                <button type="submit"
                    style="background:#f97316;color:white;border:none;border-radius:10px;padding:8px 14px;font-weight:900;">
                    Post
                </button>
            </div>
        </form>
    </div>

    <!-- Feed -->
    @forelse ($posts as $post)
        @php
            $likesCount = $post->likes->count();
            $likedByMe = $post->likes->contains(auth()->id());
        @endphp

        <div id="post-{{ $post->id }}" style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-bottom:16px;">

            <!-- Header -->
            <div style="padding:10px 14px;background:#fff7ed;border-bottom:1px solid #fed7aa;
                        display:flex;justify-content:space-between;align-items:center;">
                <a href="{{ route('users.show', $post->user) }}"
                   style="font-weight:900;color:black;text-decoration:underline;">
                    {{ $post->user->name }}
                </a>

                <div style="font-size:13px;color:#6b7280;">
                    {{ $post->created_at->diffForHumans() }}
                </div>
            </div>

            <!-- Body -->
            <div style="padding:14px;">
                <div style="white-space:pre-wrap;">{{ $post->caption }}</div>

                <!-- Likes -->
                <div style="margin-top:10px;display:flex;align-items:center;gap:10px;">
                    <button
                        class="like-btn"
                        data-post-id="{{ $post->id }}"
                        data-liked="{{ $likedByMe ? '1' : '0' }}"
                        style="border:none;
                               background:{{ $likedByMe ? '#111' : '#f97316' }};
                               color:#fff;
                               border-radius:999px;
                               padding:8px 12px;
                               font-weight:900;
                               cursor:pointer;">
                        {{ $likedByMe ? '♥ Liked' : '♡ Like' }}
                    </button>

                    <span id="likes-count-{{ $post->id }}" style="font-weight:900;">
                        {{ $likesCount }}
                    </span>
                </div>

                <!-- Edit / Delete -->
                @if ($post->user_id == auth()->id())
                    <div style="margin-top:10px;">
                        <a href="{{ route('posts.edit', $post) }}"
                           style="color:#f97316;font-weight:800;">Edit</a>

                        <form method="POST"
                              action="{{ route('posts.destroy', $post) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Delete this post? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                               style="background:#b91c1c;color:#fff;border:none;border-radius:10px;
                                      padding:6px 10px;font-weight:800;cursor:pointer;">
                             Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:18px;">
            No posts yet.
        </div>
    @endforelse

</div>

<script>
async function toggleLike(btn) {
  const postId = btn.dataset.postId;
  const liked = btn.dataset.liked === '1';

  try {
    const response = await fetch(`/posts/${postId}/like`, {
      method: liked ? 'DELETE' : 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    if (!response.ok) {
      const text = await response.text();
      console.error('Like request failed:', response.status, text);
      return;
    }

    const data = await response.json();

    // Update button + count immediately
    btn.dataset.liked = data.liked ? '1' : '0';
    btn.textContent = data.liked ? '♥ Liked' : '♡ Like';
    btn.style.background = data.liked ? '#111' : '#f97316';

    const counter = document.getElementById(`likes-count-${postId}`);
    if (counter) counter.textContent = data.likes;

  } catch (err) {
    console.error('Like JS error:', err);
  }
}

document.querySelectorAll('.like-btn').forEach(btn => {
  btn.addEventListener('click', () => toggleLike(btn));
});
</script>


</body>
</html>


