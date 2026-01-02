<!doctype html>
<html>
<head><meta charset="utf-8"><title>Post</title></head>
<body style="margin:0;font-family:system-ui;background:#fafafa;">
<div style="max-width:900px;margin:0 auto;padding:22px 18px;">

  <a href="{{ route('feed.index') }}" style="color:#f97316;font-weight:900;text-decoration:underline;">← Back</a>

  @php
    $likesCount = $post->likes->count();
    $likedByMe = $post->likes->contains(auth()->id());
  @endphp

  <div style="margin-top:14px;background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;">
    <div style="font-weight:900;">
      <a href="{{ route('users.show', $post->user) }}" style="color:#111;text-decoration:underline;">
        {{ $post->user->name }}
      </a>
      · <span style="color:#6b7280;font-weight:700;">{{ $post->created_at->diffForHumans() }}</span>
    </div>

    <div style="margin-top:10px;white-space:pre-wrap;">{{ $post->caption }}</div>

    <!-- Likes -->
    <div style="margin-top:12px;display:flex;align-items:center;gap:10px;">
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
  </div>

  <h2 style="margin:18px 0 10px;color:#f97316;">COMMENTS</h2>

  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;">
    <form method="POST" action="{{ route('comments.store', $post) }}">
      @csrf
      <textarea name="body" rows="3" required
        style="width:100%;border:1px solid #d1d5db;border-radius:10px;padding:10px;">{{ old('body') }}</textarea>
      @error('body') <div style="color:#b91c1c;font-weight:900;margin-top:6px;">{{ $message }}</div> @enderror
      <div style="text-align:right;margin-top:8px;">
        <button type="submit" style="background:#f97316;color:#fff;border:none;border-radius:10px;padding:8px 14px;font-weight:900;">
          Comment
        </button>
      </div>
    </form>

    <hr style="margin:14px 0;">

    @forelse ($post->comments as $c)
      <div style="padding:10px 0;border-bottom:1px solid #f3f4f6;">
        <div style="font-size:13px;color:#6b7280;">
          <a href="{{ route('users.show', $c->user) }}" style="color:#111;font-weight:900;text-decoration:underline;">
            {{ $c->user->name }}
          </a>
          · {{ $c->created_at->diffForHumans() }}
        </div>
        <div style="margin-top:4px;">{{ $c->body }}</div>
      </div>
    @empty
      <div style="color:#6b7280;">No comments yet.</div>
    @endforelse
  </div>

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





