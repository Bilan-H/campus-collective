@extends('layouts.app')

@section('title', '#'.$tag->slug.' — Campus Collective')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-black mb-0">#{{ $tag->slug }}</h4>
    <a href="{{ route('feed.index') }}" class="cc-orange fw-bold text-decoration-none">Back to feed</a>
  </div>

  @forelse ($posts as $post)
    <div class="card card-soft mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <a class="fw-bold text-decoration-none text-dark" href="{{ route('users.show', $post->user) }}">
              {{ $post->user->name }}
            </a>
            <span class="text-secondary small">· {{ $post->created_at->diffForHumans() }}</span>
          </div>
          <a class="cc-orange fw-bold text-decoration-none" href="{{ route('posts.show', $post) }}">Open</a>
        </div>

        <div style="white-space:pre-wrap;line-height:1.5;">
          {{ $post->caption }}
        </div>
      </div>
    </div>
  @empty
    <div class="text-secondary">No posts for this hashtag yet.</div>
  @endforelse

  <div class="d-flex justify-content-center mt-4">
    {{ $posts->links('pagination::bootstrap-5') }}
  </div>
@endsection



