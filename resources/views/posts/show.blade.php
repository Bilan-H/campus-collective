@extends('layouts.app')

@section('title', 'Post — Campus Collective')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <a href="{{ route('feed.index') }}" class="cc-orange fw-bold text-decoration-none">
    ← Back
  </a>

  {{-- Edit / Delete (policy controlled: owner OR admin) --}}
  @canany(['update', 'delete'], $post)
    <div class="d-flex gap-2">
      @can('update', $post)
        <a href="{{ route('posts.edit', $post) }}"
           class="btn btn-outline-cc btn-sm fw-bold">
          Edit
        </a>
      @endcan

      @can('delete', $post)
        <form method="POST"
              action="{{ route('posts.destroy', $post) }}"
              onsubmit="return confirm('Delete this post? This cannot be undone.');">
          @csrf
          @method('DELETE')
          <button type="submit"
                  class="btn btn-danger btn-sm fw-bold">
            Delete
          </button>
        </form>
      @endcan
    </div>
  @endcanany
</div>

@php
  $likesCount = $post->likes->count();
  $likedByMe = $post->likes->contains(auth()->id());
@endphp

{{-- Post card --}}
<div class="card card-soft mb-4" id="post-{{ $post->id }}">
  <div class="card-body">

    <div class="d-flex justify-content-between align-items-center mb-2">
      <div class="fw-bold">
        <a href="{{ route('users.show', $post->user) }}"
           class="text-dark text-decoration-none fw-black">
          {{ $post->user->name }}
        </a>
        <span class="text-secondary fw-normal">
          · {{ $post->created_at->diffForHumans() }}
        </span>
      </div>
    </div>

    <div style="white-space:pre-wrap;line-height:1.55;">
      {{ $post->caption }}
    </div>

    {{-- Image --}}
    @if ($post->image_path)
      <div class="mt-3">
        <img src="{{ asset('storage/'.$post->image_path) }}"
             class="img-fluid rounded border"
             alt="Post image">
      </div>
    @endif

    {{-- Likes --}}
    <div class="d-flex align-items-center gap-2 mt-3">
      <button
        class="like-btn btn btn-sm {{ $likedByMe ? 'btn-dark' : 'btn-cc' }}"
        data-post-id="{{ $post->id }}"
        data-liked="{{ $likedByMe ? '1' : '0' }}"
        type="button"
      >
        <span class="like-label">
          {{ $likedByMe ? '♥ Liked' : '♡ Like' }}
        </span>
      </button>

      <span id="likes-count-{{ $post->id }}" class="fw-bold">
        {{ $likesCount }}
      </span>
    </div>
  </div>
</div>

{{-- Comments --}}
<div class="cc-orange fw-black mb-2" style="font-weight:900;letter-spacing:.8px;">
  COMMENTS
</div>

<div class="card card-soft">
  <div class="card-body">

    {{-- Inline error placeholder for AJAX --}}
    <div id="comment-error" class="alert alert-danger py-2 d-none"></div>

    {{-- Comment form (AJAX-enabled) --}}
    <form id="comment-form" method="POST" action="{{ route('comments.store', $post) }}">
      @csrf

      <div class="mb-2">
        <textarea id="comment-body" name="body" rows="3" required
                  class="form-control"
                  style="border-radius:12px;">{{ old('body') }}</textarea>

        @error('body')
          <div class="text-danger small fw-bold mt-2">{{ $message }}</div>
        @enderror
      </div>

      <div class="text-end">
        <button id="comment-submit" type="submit"
                class="btn btn-cc px-4 fw-bold">
          Comment
        </button>
      </div>
    </form>

    <hr class="my-3">

    {{-- Comment list --}}
    <div id="comments-list">
      @forelse ($post->comments as $c)
        <div class="py-2 border-bottom">
          <div class="small text-secondary">
            <a href="{{ route('users.show', $c->user) }}"
               class="text-dark fw-bold text-decoration-none">
              {{ $c->user->name }}
            </a>
            · {{ $c->created_at->diffForHumans() }}
          </div>
          <div class="mt-1">{{ $c->body }}</div>
        </div>
      @empty
        <div id="no-comments" class="text-secondary">No comments yet.</div>
      @endforelse
    </div>

  </div>
</div>

@endsection

@push('scripts')
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

    if (!response.ok) return;

    const data = await response.json();

    btn.dataset.liked = data.liked ? '1' : '0';

    const label = btn.querySelector('.like-label');
    if (label) label.textContent = data.liked ? '♥ Liked' : '♡ Like';

    btn.classList.toggle('btn-dark', data.liked);
    btn.classList.toggle('btn-cc', !data.liked);

    const counter = document.getElementById(`likes-count-${postId}`);
    if (counter) counter.textContent = data.likes;

  } catch (err) {
    console.error(err);
  }
}

document.addEventListener('click', (e) => {
  const btn = e.target.closest('.like-btn');
  if (!btn) return;
  e.preventDefault();
  toggleLike(btn);
});


function showCommentError(msg) {
  const box = document.getElementById('comment-error');
  if (!box) return;
  box.textContent = msg;
  box.classList.remove('d-none');
}

function clearCommentError() {
  const box = document.getElementById('comment-error');
  if (!box) return;
  box.textContent = '';
  box.classList.add('d-none');
}

function escapeHtml(str) {
  return String(str)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('comment-form');
  const textarea = document.getElementById('comment-body');
  const submitBtn = document.getElementById('comment-submit');
  const list = document.getElementById('comments-list');
  const noComments = document.getElementById('no-comments');

  if (!form || !textarea || !submitBtn || !list) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearCommentError();

    const body = textarea.value.trim();
    if (!body) return;

    submitBtn.disabled = true;

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ body })
      });

      if (!response.ok) {
        let msg = 'Could not post comment.';
        try {
          const data = await response.json();
          if (data?.message) msg = data.message;
          if (data?.errors?.body?.[0]) msg = data.errors.body[0];
        } catch (_) {}
        showCommentError(msg);
        return;
      }

      const data = await response.json();

      if (noComments) noComments.remove();

      const html = `
        <div class="py-2 border-bottom">
          <div class="small text-secondary">
            <a href="/users/${data.user.id}" class="text-dark fw-bold text-decoration-none">
              ${escapeHtml(data.user.name)}
            </a>
            · ${escapeHtml(data.created_human)}
          </div>
          <div class="mt-1">${escapeHtml(data.body)}</div>
        </div>
      `;
      list.insertAdjacentHTML('afterbegin', html);

      textarea.value = '';

    } catch (err) {
      console.error(err);
      showCommentError('Network error posting comment.');
    } finally {
      submitBtn.disabled = false;
    }
  });
});
</script>
@endpush

