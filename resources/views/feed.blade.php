@extends('layouts.app')

@section('title', 'Feed — Campus Collective')

@section('content')

  {{-- Create post --}}
  <div class="card card-soft mb-4">
    <div class="card-body">
      <h5 class="fw-black cc-orange mb-3" style="font-weight:900;letter-spacing:.5px;">FEED</h5>
      <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
      <label class="form-label fw-bold">Share something</label>

    <textarea
    name="caption"
    rows="3"
    required
    class="form-control"
    placeholder="Write a post… include hashtags like #law #cs"
    >{{ old('caption') }}</textarea>

    {{-- Image upload --}}
    <input
     type="file"
     name="image"
     accept="image/*"
     class="form-control mt-2"
    />

      @error('image')
        <div class="text-danger small fw-bold mt-2">{{ $message }}</div>
      @enderror

        @error('caption')
          <div class="text-danger small fw-bold mt-2">{{ $message }}</div>
        @enderror

        <div class="d-flex justify-content-end mt-3">
          <button type="submit" class="btn btn-cc px-4">Post</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Feed --}}
  @forelse ($posts as $post)
    @php
      $likesCount = $post->likes->count();
      $likedByMe = $post->likes->contains(auth()->id());
    @endphp

    <div id="post-{{ $post->id }}" class="card card-soft mb-3">
      {{-- Header --}}
      <div class="card-header bg-white border-0 pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
            <a href="{{ route('users.show', $post->user) }}"
               class="text-decoration-none fw-bold"
               style="color:#111;">
              {{ $post->user->name }}
            </a>
            <span class="text-secondary small">· {{ $post->created_at->diffForHumans() }}</span>
          </div>

          {{-- Owner actions --}}
          @if ($post->user_id == auth()->id())
            <div class="d-flex align-items-center gap-2">
              <a href="{{ route('posts.edit', $post) }}" class="cc-orange fw-bold text-decoration-none">
                Edit
              </a>

              <form method="POST"
                    action="{{ route('posts.destroy', $post) }}"
                    class="m-0"
                    onsubmit="return confirm('Delete this post? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger fw-bold">
                  Delete
                </button>
              </form>
            </div>
          @endif
        </div>
      </div>
{{-- Body --}}
<div class="card-body pt-3">

  {{-- Caption --}}
  <div class="mb-3" style="white-space:pre-wrap;line-height:1.5;">
    {{ $post->caption }}
  </div>
 


  {{-- Image (only if uploaded) --}}
  @if ($post->image_path)
    <div class="mb-3">
      <img
        src="{{ asset('storage/' . $post->image_path) }}"
        alt="Post image"
        class="img-fluid rounded"
        style="border:1px solid #e5e7eb;"
      >
    </div>
  @endif

  {{-- Likes row --}}
  <div class="d-flex align-items-center gap-2">
          <button
            type="button"
            class="btn btn-sm fw-bold like-btn {{ $likedByMe ? 'btn-dark' : 'btn-cc' }}"
            data-post-id="{{ $post->id }}"
            data-liked="{{ $likedByMe ? '1' : '0' }}"
          >
            {{ $likedByMe ? '♥ Liked' : '♡ Like' }}
          </button>

          <span class="fw-bold" id="likes-count-{{ $post->id }}">{{ $likesCount }}</span>

          <a class="ms-2 text-decoration-none cc-orange fw-bold"
             href="{{ route('posts.show', $post) }}">
            View post
          </a>
        </div>
      </div>
    </div>

  @empty
    <div class="card card-soft">
      <div class="card-body text-secondary">
        No posts yet.
      </div>
    </div>
  @endforelse

{{-- Pagination --}}
  <div class="d-flex justify-content-center mt-4">
  {{ $posts->links('pagination::bootstrap-5') }}
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

        // update state
        btn.dataset.liked = data.liked ? '1' : '0';
        btn.textContent = data.liked ? '♥ Liked' : '♡ Like';

        // swap bootstrap button classes
        btn.classList.toggle('btn-dark', data.liked);
        btn.classList.toggle('btn-cc', !data.liked);

        const counter = document.getElementById(`likes-count-${postId}`);
        if (counter) counter.textContent = data.likes;

      } catch (err) {
        console.error('Like JS error:', err);
      }
    }

    // bind once DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', () => toggleLike(btn));
      });
    });
  </script>

@endsection



