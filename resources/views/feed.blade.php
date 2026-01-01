<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Feed — Campus Collective</title>
</head>

<body style="margin:0;font-family:system-ui;background:#fafafa;">
<div style="max-width:900px;margin:0 auto;padding:22px 18px;">

    <!-- Top bar -->
    <div style="display:flex;align-items:center;justify-content:center;position:relative;margin-bottom:20px;">
        <div style="position:absolute;left:0;color:#f97316;font-weight:900;">
            FEED
        </div>

        <div style="text-align:center;">
            <div style="color:#f97316;font-weight:900;font-size:28px;">
                CAMPUS COLLECTIVE ;)
            </div>

            <div style="font-size:13px;color:#666;margin-top:6px;">
                Logged in as <strong>{{ auth()->user()->name }}</strong>
                · <a href="{{ route('profile.edit') }}" style="color:#f97316;font-weight:800;">Profile</a>
                ·
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit"
                        style="border:none;background:none;color:#f97316;font-weight:800;cursor:pointer;">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Create post -->
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;margin-bottom:18px;">
        @if (session('success'))
            <div style="background:#ecfdf5;border:1px solid #bbf7d0;padding:10px;border-radius:10px;margin-bottom:10px;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('posts.store') }}">
            @csrf

            <label style="font-weight:800;">Share something</label>

            <textarea name="caption" rows="3" required
                style="width:100%;margin-top:8px;border:1px solid #d1d5db;border-radius:10px;padding:10px;">{{ old('caption') }}</textarea>

            @error('caption')
                <div style="color:#b91c1c;font-size:13px;margin-top:6px;">{{ $message }}</div>
            @enderror

            <div style="text-align:right;margin-top:10px;">
                <button type="submit"
                    style="background:#f97316;color:white;border:none;border-radius:10px;padding:8px 14px;font-weight:900;">
                    Post
                </button>
            </div>
        </form>
    </div>

    <!-- Feed -->
    @forelse ($posts as $post)
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-bottom:16px;">

            <!-- Header -->
            <div style="padding:10px 14px;background:#fff7ed;border-bottom:1px solid #fed7aa;
                        display:flex;justify-content:space-between;align-items:center;">
                <a href="{{ route('users.show', $post->user) }}"
                   style="font-weight:900;color:black;text-decoration:underline;">
                    {{ $post->user->name }}
                </a>

                <div style="font-size:13px;color:#6b7280;">
                    {{ $post->created_at->diffForHumans() }}
                </div>
            </div>

            <!-- Body -->
            <div style="padding:14px;">
                <div style="white-space:pre-wrap;">{{ $post->caption }}</div>

                <!-- Edit / Delete (ONLY owner) -->
                @if ($post->user_id == auth()->id())
                    <div style="margin-top:10px;">
                        <a href="{{ route('posts.edit', $post) }}"
                           style="color:#f97316;font-weight:800;">Edit</a>

                        <form method="POST"
                              action="{{ route('posts.destroy', $post) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Delete this post? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                               style="background:#b91c1c;color:#fff;border:none;border-radius:10px;
                                      padding:6px 10px;font-weight:800;cursor:pointer;">
                             Delete
                      </button>
                     </form>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:18px;">
            No posts yet.
        </div>
    @endforelse

</div>
</body>
</html>
