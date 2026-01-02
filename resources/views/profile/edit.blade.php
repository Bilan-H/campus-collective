<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profile — Campus Collective</title>
</head>

<body style="margin:0;font-family:system-ui;background:#fafafa;">
<div style="max-width:900px;margin:0 auto;padding:28px 18px;position:relative;">

    <!-- Logout button (top right) -->
    <div style="position:absolute;top:22px;right:22px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                style="border:none;background:none;color:#f97316;font-weight:900;
                       text-decoration:underline;cursor:pointer;font-size:14px;">
                Log out
            </button>
        </form>
    </div>

    <!-- Back link -->
    <a href="{{ route('feed.index') }}"
       style="color:#f97316;font-weight:900;text-decoration:underline;">
        ← Back to Feed
    </a>

    <!-- Profile card -->
    <div style="margin-top:26px;background:#fff;border:1px solid #e5e7eb;border-radius:18px;padding:18px;">

        <!-- Title -->
        <div style="color:#f97316;font-weight:900;letter-spacing:1px;font-size:26px;">
            EDIT PROFILE
        </div>

        <!-- Followers / Following -->
        <div style="margin-top:12px;display:flex;gap:20px;flex-wrap:wrap;align-items:center;">
            <div style="font-weight:900;color:#f97316;">
                FOLLOWERS:
                <span style="color:#111;">{{ $followersCount ?? 0 }}</span>
            </div>

            <div style="font-weight:900;color:#f97316;">
                FOLLOWING:
                <span style="color:#111;">{{ $followingCount ?? 0 }}</span>
            </div>

            <div style="margin-left:auto;color:#6b7280;font-size:13px;">
                Logged in as <strong>{{ $user->name }}</strong>
            </div>
        </div>

        <!-- Success message -->
        @if (session('success'))
            <div style="margin-top:14px;padding:10px;background:#ecfdf5;
                        border:1px solid #bbf7d0;border-radius:10px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Edit form -->
        <form method="POST" action="{{ route('profile.update') }}" style="margin-top:16px;">
            @csrf
            @method('PATCH')

            <label style="font-weight:800;">Name</label><br>
            <input name="name"
                   value="{{ old('name', $user->name) }}"
                   style="width:100%;box-sizing:border-box;padding:10px;
                          border:1px solid #d1d5db;border-radius:12px;
                          margin:6px 0 12px;">
            @error('name')
                <div style="color:#b91c1c;font-weight:700;">{{ $message }}</div>
            @enderror

            <label style="font-weight:800;">Email</label><br>
            <input name="email"
                   value="{{ old('email', $user->email) }}"
                   style="width:100%;box-sizing:border-box;padding:10px;
                          border:1px solid #d1d5db;border-radius:12px;
                          margin:6px 0 12px;">
            @error('email')
                <div style="color:#b91c1c;font-weight:700;">{{ $message }}</div>
            @enderror

            <button type="submit"
                style="border:none;border-radius:12px;padding:10px 14px;
                       background:#f97316;color:#fff;font-weight:900;
                       cursor:pointer;">
                Save
            </button>
        </form>
    </div>

    <!-- Posts section -->
    <div style="margin-top:18px;background:#fff;border:1px solid #e5e7eb;
                border-radius:18px;padding:18px;">

        <div style="color:#f97316;font-weight:900;letter-spacing:1px;
                    font-size:20px;margin-bottom:12px;">
            POSTS
        </div>

        <div style="max-height:55vh;overflow-y:auto;padding-right:6px;">
            @forelse (($posts ?? []) as $post)
                <div style="border:1px solid #e5e7eb;border-radius:14px;
                            padding:14px;margin-bottom:12px;background:#fafafa;">
                    <div style="color:#6b7280;font-size:13px;margin-bottom:8px;">
                        {{ $post->created_at->diffForHumans() }}
                        ·
                        <a href="{{ route('posts.show', $post) }}"
                           style="color:#f97316;font-weight:900;
                                  text-decoration:underline;">
                            Open
                        </a>
                    </div>

                    <div style="white-space:pre-wrap;line-height:1.45;">
                        {{ $post->caption }}
                    </div>
                </div>
            @empty
                <div style="color:#6b7280;">No posts yet.</div>
            @endforelse
        </div>
    </div>

</div>
</body>
</html>

