@extends('layouts.app')

@section('title', 'Profile — Campus Collective')

@section('content')

<div class="d-flex justify-content-between align-items-start mb-3">
  <div>
    <h1 class="mb-1" style="color:#f97316;font-weight:900;letter-spacing:.8px;">EDIT PROFILE</h1>

    <div class="d-flex gap-4 flex-wrap mt-2">
      <div style="font-weight:900;color:#f97316;">
        FOLLOWERS: <span style="color:#111;">{{ $followersCount ?? 0 }}</span>
      </div>
      <div style="font-weight:900;color:#f97316;">
        FOLLOWING: <span style="color:#111;">{{ $followingCount ?? 0 }}</span>
      </div>
    </div>
  </div>

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-link p-0"
      style="color:#f97316;font-weight:900;text-decoration:underline;">
      Log out
    </button>
  </form>
</div>

@if (session('success'))
  <div class="alert alert-success py-2 mb-3">
    {{ session('success') }}
  </div>
@endif

<div class="card mb-4">
  <div class="card-body">
    <form method="POST" action="{{ route('profile.update') }}">
      @csrf
      @method('PATCH')

      <div class="mb-3">
        <label class="form-label fw-bold">Name</label>
        <input class="form-control" name="name" value="{{ old('name', $user->name) }}">
        @error('name') <div class="text-danger small fw-bold mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Email</label>
        <input class="form-control" name="email" value="{{ old('email', $user->email) }}">
        @error('email') <div class="text-danger small fw-bold mt-2">{{ $message }}</div> @enderror
      </div>

      <div class="d-flex justify-content-end">
        <button class="btn btn-cc px-4" type="submit">Save</button>
      </div>
    </form>

    <hr class="my-4">

    <div class="text-danger fw-bold mb-2">Danger zone</div>
    <form method="POST" action="{{ route('profile.destroy') }}"
          onsubmit="return confirm('Delete your account? This cannot be undone.');">
      @csrf
      @method('DELETE')
      <button class="btn btn-danger" type="submit">Delete account</button>
    </form>
  </div>
</div>

{{-- POSTS --}}
<div class="card mb-4">
  <div class="card-body">
    <div style="color:#f97316;font-weight:900;letter-spacing:.8px;font-size:18px;margin-bottom:12px;">
      POSTS
    </div>

    @forelse (($posts ?? []) as $p)
      <div class="border rounded-3 p-3 mb-3 bg-light">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="text-muted small">{{ $p->created_at->diffForHumans() }}</div>

          <div class="d-flex gap-2">
            <a href="{{ route('posts.show', $p) }}" class="fw-bold"
               style="color:#f97316;text-decoration:underline;">
              Open
            </a>

            @if ($p->user_id == auth()->id())
              <a href="{{ route('posts.edit', $p) }}" class="fw-bold"
                 style="color:#f97316;text-decoration:underline;">
                Edit
              </a>
            @endif
          </div>
        </div>

        <div style="white-space:pre-wrap;">{{ $p->caption }}</div>

        @if ($p->image_path)
          <div class="mt-3">
            <img
              src="{{ asset('storage/'.$p->image_path) }}"
              class="img-fluid rounded border"
              alt="Post image">
          </div>
        @endif
      </div>
    @empty
      <div class="text-muted">No posts yet.</div>
    @endforelse
  </div>
</div>

{{-- COMMENTS --}}
<div class="card">
  <div class="card-body">
    <div style="color:#f97316;font-weight:900;letter-spacing:.8px;font-size:18px;margin-bottom:12px;">
      COMMENTS
    </div>

    @forelse (($comments ?? []) as $c)
      <div class="border rounded-3 p-3 mb-3 bg-light">
        <div class="d-flex justify-content-between align-items-center">
          <div class="text-muted small">
            {{ $c->created_at->diffForHumans() }}
          </div>

          @if ($c->post)
            <a href="{{ route('posts.show', $c->post) }}" class="fw-bold"
               style="color:#f97316;text-decoration:underline;">
              View post
            </a>
          @else
            <span class="text-muted small">Post deleted</span>
          @endif
        </div>

        <div class="mt-2" style="white-space:pre-wrap;">{{ $c->body }}</div>

        @if ($c->post)
          <div class="mt-2 small text-muted">
            On: <span class="fw-bold">{{ $c->post->user->name ?? 'Unknown' }}</span>’s post
          </div>
        @endif
      </div>
    @empty
      <div class="text-muted">No comments yet.</div>
    @endforelse
  </div>
</div>

@endsection
