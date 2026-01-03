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
          <div>
            <span style="color:#f97316;font-weight:900;">Followers</span>
            <span style="font-weight:900;">{{ $followersCount }}</span>
          </div>
          <div>
            <span style="color:#f97316;font-weight:900;">Following</span>
            <span style="font-weight:900;">{{ $followingCount }}</span>
          </div>
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

  {{-- POSTS --}}
  <h2 style="margin:18px 0 10px;color:#f97316;letter-spacing:1px;">POSTS</h2>

  @forelse ($posts as $post)
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-bottom:16px;">
      <div style="padding:10px 14px;background:#fff7ed;border-bottom:1px solid #fed7aa;display:flex;justify-content:space-between;align-items:center;">
        <div style="font-weight:900;">{{ $post->created_at->diffForHumans() }}</div>
        <a href="{{ route('posts.show', $post) }}" style="color:#111;font-weight:900;text-decoration:underline;">Open</a>
      </div>

      <div style="padding:14px;white-space:pre-wrap;">{{ $post->caption }}</div>

      {{-- optional image preview --}}
      @if (!empty($post->image_path))
        <div style="padding:0 14px 14px;">
          <img src="{{ asset('storage/'.$post->image_path) }}"
               alt="Post image"
               style="width:100%;max-height:420px;object-fit:cover;border-radius:12px;border:1px solid #e5e7eb;">
        </div>
      @endif
    </div>
  @empty
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px;color:#6b7280;">
      No posts yet.
    </div>
  @endforelse

  {{-- COMMENTS --}}
  <h2 style="margin:22px 0 10px;color:#f97316;letter-spacing:1px;">COMMENTS</h2>

  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;">
    @forelse (($comments ?? []) as $c)
      <div style="padding:10px 0;border-bottom:1px solid #f3f4f6;">
        <div style="display:flex;justify-content:space-between;gap:10px;align-items:center;">
          <div style="font-size:13px;color:#6b7280;">
            {{ $c->created_at->diffForHumans() }}
            ·
            <a href="{{ route('posts.show', $c->post) }}"
               style="color:#111;font-weight:900;text-decoration:underline;">
              View post
            </a>
          </div>
        </div>

        <div style="margin-top:6px;white-space:pre-wrap;">{{ $c->body }}</div>

        {{-- small post context preview --}}
        @if (!empty($c->post?->caption))
          <div style="margin-top:8px;padding:10px;border:1px solid #e5e7eb;border-radius:12px;background:#fafafa;color:#374151;">
            <div style="font-size:12px;color:#6b7280;font-weight:900;margin-bottom:6px;">
              On:
            </div>
            <div style="white-space:pre-wrap;line-height:1.4;">
              {{ \Illuminate\Support\Str::limit($c->post->caption, 140) }}
            </div>
          </div>
        @endif
      </div>
    @empty
      <div style="color:#6b7280;">No comments yet.</div>
    @endforelse
  </div>

</div>
</body>
</html>







