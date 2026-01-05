@extends('layouts.app')

@section('title', 'Notifications — Campus Collective')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="cc-orange fw-black" style="font-weight:900;letter-spacing:.8px;">
      NOTIFICATIONS
    </div>

    <form method="POST" action="{{ route('notifications.readAll') }}" class="m-0">
      @csrf
      <button class="btn btn-sm btn-outline-dark fw-bold" type="submit">
        Mark all as read
      </button>
    </form>
  </div>

  <div class="card card-soft">
    <div class="card-body">

      @forelse($notifications as $n)
        @php
          $data = $n->data ?? [];
          $postId = $data['post_id'] ?? null;

          $isLiked = $n->type === \App\Notifications\PostLiked::class;
          $isCommented = $n->type === \App\Notifications\PostCommented::class;
        @endphp

        <div class="py-3 border-bottom">
          <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
              <div class="fw-bold">
                @if ($isLiked)
                  {{ $data['liker_name'] ?? 'Someone' }} liked your post
                @elseif ($isCommented)
                  {{ $data['commenter_name'] ?? 'Someone' }} commented on your post
                @else
                  Notification (unknown type)
                @endif
              </div>

              @if ($isCommented && !empty($data['comment_body']))
                <div class="text-secondary small mt-1">
                  “{{ \Illuminate\Support\Str::limit($data['comment_body'], 120) }}”
                </div>
              @endif

              <div class="text-secondary small mt-1">
                {{ $n->created_at->diffForHumans() }}
                @if (is_null($n->read_at))
                  · <span class="badge bg-dark">New</span>
                @endif
              </div>

              @if ($postId)
                <div class="mt-2">
                  <a class="btn btn-sm btn-cc fw-bold" href="{{ route('posts.show', $postId) }}">
                    Open post
                  </a>
                </div>
              @endif
            </div>

            @if (is_null($n->read_at))
              <form method="POST" action="{{ route('notifications.read', $n->id) }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary fw-bold">
                  Mark read
                </button>
              </form>
            @endif
          </div>
        </div>

      @empty
        <div class="text-secondary">No notifications yet.</div>
      @endforelse

    </div>
  </div>
@endsection

