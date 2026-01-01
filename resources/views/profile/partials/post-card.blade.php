@php
  $isOwner = auth()->check() && ($post->user_id == auth()->id());
@endphp

<div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-bottom:16px;">
  <div style="padding:10px 14px;background:#fff7ed;border-bottom:1px solid #fed7aa;display:flex;justify-content:space-between;align-items:center;">
    <a href="{{ route('users.show', $post->user) }}" style="font-weight:900;color:black;text-decoration:underline;">
      {{ $post->user->name }}
    </a>
    <div style="font-size:13px;color:#6b7280;">
      {{ $post->created_at->diffForHumans() }}
    </div>
  </div>

  <div style="padding:14px;">
    <div style="white-space:pre-wrap;">{{ $post->caption }}</div>

    @if ($isOwner)
      <div style="margin-top:10px;">
        <a href="{{ route('posts.edit', $post) }}" style="color:#f97316;font-weight:800;">Edit</a>
        <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit"
            style="border:none;background:none;color:#b91c1c;font-weight:800;cursor:pointer;"
            onclick="return confirm('Delete this post?')">
            Delete
          </button>
        </form>
      </div>
    @endif

    <div style="margin-top:12px;">
      <a href="{{ route('posts.show', $post) }}" style="color:#111;font-weight:800;text-decoration:underline;">Open post</a>
    </div>
  </div>
</div>
