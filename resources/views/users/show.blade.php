<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{ $user->name }} — Campus Collective</title>
</head>
<body style="margin:0;font-family:system-ui;background:#fafafa;">
<div style="max-width:900px;margin:0 auto;padding:22px 18px;">

  <a href="{{ route('feed.index') }}" style="color:#f97316;font-weight:900;text-decoration:underline;">← Back to Feed</a>

  <div style="margin-top:14px;background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:14px;">
      <div>
        <div style="font-size:24px;font-weight:900;color:#111;">{{ $user->name }}</div>
        <div style="margin-top:8px;display:flex;gap:14px;">
          <div><span style="color:#f97316;font-weight:900;">Followers</span> <span style="font-weight:900;">{{ $followersCount }}</span></div>
          <div><span style="color:#f97316;font-weight:900;">Following</span> <span style="font-weight:900;">{{ $followingCount }}</span></div>
        </div>
      </div>

      {{-- Follow button (only if viewing someone else) --}}
      @if (auth()->id() !== $user->id)
        <div>
          @if (!empty($isFollowing) && $isFollowing)
            <form method="POST" action="{{ route('follow.destroy', $user) }}">
              @csrf
              @method('DELETE')
              <button type="submit" style="background:#111;color:#fff;border:none;border-radius:10px;padding:10px 14px;font-weight:900;cursor:pointer;">
                Following ✓ (Unfollow)
              </button>
            </form>
          @else
            <form method="POST" action="{{ route('follow.store', $user) }}">
              @csrf
              <button type="submit" style="background:#f97316;color:#fff;border:none;border-radius:10px;padding:10px 14px;font-weight:900;cursor:pointer;">
                Follow
              </button>
            </form>
          @endif
        </div>
      @endif
    </div>
  </div>

  <h2 style="margin:18px 0 10px;color:#f97316;letter-spacing:1px;">POSTS</h2>

  @forelse ($posts as $post)
    {{-- Reuse your feed post card layout --}}
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-bottom:16px;">
      <div style="padding:10px 14px;background:#fff7ed;border-bottom:1px solid #fed7aa;display:flex;justify-content:space-between;align-items:center;">
        <div style="font-weight:900;">{{ $post->created_at->diffForHumans() }}</div>
        <a href="{{ route('posts.show', $post) }}" style="color:#111;font-weight:900;text-decoration:underline;">Open</a>
      </div>
      <div style="padding:14px;white-space:pre-wrap;">{{ $post->caption }}</div>
    </div>
  @empty
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px;color:#6b7280;">
      No posts yet.
    </div>
  @endforelse

</div>
</body>
</html>






