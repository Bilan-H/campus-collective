@extends('layouts.app')

@section('title', 'Feed — Campus Collective')

@section('content')

{{-- Dev Spotlight: External API --}}
@php
  $hasUser = is_array($githubUser ?? null) && empty(($githubUser['message'] ?? null));
  $hasRepo = is_array($githubRepo ?? null) && empty(($githubRepo['message'] ?? null));
@endphp

@if ($hasUser || $hasRepo)
  <div class="card card-soft mb-4">
    <div class="card-body d-flex align-items-center gap-3">

      {{-- Avatar --}}
      @if ($hasUser && !empty($githubUser['avatar_url']))
        <img
          src="{{ $githubUser['avatar_url'] }}"
          alt="GitHub avatar"
          style="width:56px;height:56px;border-radius:999px;border:1px solid #e5e7eb;">
      @endif

      <div class="flex-grow-1">
        <div class="fw-black cc-orange" style="letter-spacing:.5px;">
          Dev Spotlight
        </div>

        {{-- User --}}
        @if ($hasUser)
          <div class="small text-secondary">
            GitHub: <strong>{{ $githubUser['login'] ?? 'Unknown' }}</strong>
            · Repos: {{ $githubUser['public_repos'] ?? 0 }}
            · Followers: {{ $githubUser['followers'] ?? 0 }}
          </div>
        @else
          <div class="small text-secondary">
            GitHub user data unavailable.
          </div>
        @endif

        {{-- Repo --}}
        @if ($hasRepo)
          <div class="mt-2">
            @if (!empty($githubRepo['html_url']))
              <a href="{{ $githubRepo['html_url'] }}" target="_blank"
                 class="text-decoration-none fw-bold" style="color:#111;">
                {{ $githubRepo['full_name'] ?? 'Repository' }}
              </a>
            @else
              <div class="fw-bold" style="color:#111;">
                {{ $githubRepo['full_name'] ?? 'Repository' }}
              </div>
            @endif

            <div class="small text-secondary">
              ⭐ {{ $githubRepo['stargazers_count'] ?? 0 }}
              · Forks: {{ $githubRepo['forks_count'] ?? 0 }}
              · Issues: {{ $githubRepo['open_issues_count'] ?? 0 }}
            </div>

            @if (!empty($githubRepo['description']))
              <div class="small mt-1">{{ $githubRepo['description'] }}</div>
            @endif
          </div>
        @else
          <div class="small text-secondary mt-2">
            Repo data unavailable (check repo name or API rate limit).
          </div>
        @endif
      </div>
    </div>
  </div>
@endif

  {{-- Create post --}}
  <div class="card card-soft mb-4">
    <div class="card-body">
      <h5 class="fw-black cc-orange mb-3" style="letter-spacing:.5px;">FEED</h5>

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
      $likedByMe  = $post->likes->contains(auth()->id());

      preg_match_all('/#([A-Za-z0-9_]+)/', $post->caption ?? '', $m);
      $tags = array_values(array_unique($m[1] ?? []));
    @endphp

    <div id="post-{{ $post->id }}" class="card card-soft mb-3">
      {{-- Header --}}
      <div class="card-header bg-white border-0 pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
            <a href="{{ route('users.show', $post->user) }}"
               class="fw-bold text-decoration-none"
               style="color:#111;">
              {{ $post->user->name }}
            </a>
            <span class="text-secondary small">
              · {{ $post->created_at->diffForHumans() }}
            </span>
          </div>
        </div>
      </div>

      {{-- Body --}}
      <div class="card-body pt-3">

        {{-- Caption --}}
        <div class="mb-2" style="white-space:pre-wrap;line-height:1.5;">
          {{ $post->caption }}
        </div>

        {{-- Hashtags --}}
        @if (count($tags) > 0)
          <div class="mb-3 d-flex flex-wrap gap-2">
            @foreach ($tags as $t)
              <a href="{{ route('tags.show', strtolower($t)) }}"
                 class="text-decoration-none"
                 style="font-size:12px;font-weight:900;color:#f97316;
                        border:1px solid #fed7aa;background:#fff7ed;
                        padding:6px 10px;border-radius:999px;">
                #{{ $t }}
              </a>
            @endforeach
          </div>
        @endif

        {{-- Image --}}
        @if ($post->image_path)
          <div class="mb-3">
            <img
              src="{{ asset('storage/' . $post->image_path) }}"
              alt="Post image"
              class="img-fluid rounded"
              style="border:1px solid #e5e7eb;">
          </div>
        @endif

        {{-- Likes --}}
        <div class="d-flex align-items-center gap-2">
          <button
            type="button"
            class="btn btn-sm fw-bold like-btn {{ $likedByMe ? 'btn-dark' : 'btn-cc' }}"
            data-post-id="{{ $post->id }}"
            data-liked="{{ $likedByMe ? '1' : '0' }}">
            {{ $likedByMe ? '♥ Liked' : '♡ Like' }}
          </button>

          <span class="fw-bold" id="likes-count-{{ $post->id }}">
            {{ $likesCount }}
          </span>

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

@endsection

@push('scripts')
<script>
  async function toggleLike(btn) {
    const postId = btn.dataset.postId;
    const liked  = btn.dataset.liked === '1';

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
      btn.textContent  = data.liked ? '♥ Liked' : '♡ Like';

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
</script>
@endpush

