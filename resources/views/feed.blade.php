<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Feed — Campus Collective</title>
</head>
<body style="margin:0;font-family:system-ui;background:#fafafa;">
    <div style="max-width:900px;margin:0 auto;padding:22px 18px;">

        <!-- Top bar -->
        <div style="display:flex;align-items:center;justify-content:center;position:relative;margin-bottom:16px;">
            <div style="position:absolute;left:0;color:#f97316;font-weight:900;letter-spacing:1px;">
                FEED
            </div>

            <div style="text-align:center;">
                <div style="color:#f97316;font-weight:900;letter-spacing:1px;font-size:28px;">
                    CAMPUS COLLECTIVE ;)
                </div>
                <div style="color:#666;font-size:13px;margin-top:6px;">
                    Logged in as <strong>{{ auth()->user()->name }}</strong>
                    · <a style="color:#f97316;font-weight:800;text-decoration:underline;" href="{{ route('profile.edit') }}">Profile</a>
                    · <form style="display:inline;" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="border:none;background:none;color:#f97316;font-weight:800;text-decoration:underline;cursor:pointer;padding:0;">
                            Logout
                        </button>
                      </form>
                </div>
            </div>
        </div>

        <!-- Create post -->
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;margin-bottom:14px;">
            @if (session('success'))
                <div style="padding:10px;border-radius:10px;background:#ecfdf5;border:1px solid #bbf7d0;margin-bottom:10px;">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('posts.store') }}">
                @csrf

                <label for="caption" style="display:block;font-weight:800;margin-bottom:8px;">
                    Share something
                </label>

                <textarea id="caption" name="caption" rows="3" required
                    style="width:100%;box-sizing:border-box;border:1px solid #d1d5db;border-radius:12px;padding:10px;resize:vertical;"
                    placeholder="Write a post… include hashtags like #law #cs">{{ old('caption') }}</textarea>

                @error('caption')
                    <div style="margin-top:8px;color:#b91c1c;font-size:13px;font-weight:700;">{{ $message }}</div>
                @enderror

                <div style="display:flex;justify-content:flex-end;margin-top:10px;">
                    <button type="submit"
                        style="border:none;border-radius:12px;padding:10px 14px;background:#f97316;color:#fff;font-weight:900;cursor:pointer;">
                        Post
                    </button>
                </div>
            </form>
        </div>

        <!-- Scrollable feed -->
        <div style="max-height:70vh;overflow-y:auto;padding-right:6px;">
            @forelse ($posts as $post)
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;margin-bottom:14px;overflow:hidden;">

                    <!-- Username bar -->
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px;background:#fff7ed;border-bottom:1px solid #fed7aa;">
                        <a href="{{ route('users.show', $post->user) }}"
                           style="color:#111;font-weight:900;text-decoration:underline;">
                            {{ $post->user->name }}
                        </a>

                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="color:#6b7280;font-size:13px;">
                                {{ $post->created_at->diffForHumans() }}
                            </div>

                            <!-- Edit/Delete only for YOUR posts -->
                            @if ($post->user_id === auth()->id())
                                <a href="{{ route('posts.edit', $post) }}"
                                   style="color:#f97316;font-weight:900;text-decoration:underline;font-size:13px;">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="border:none;background:none;color:#b91c1c;font-weight:900;text-decoration:underline;cursor:pointer;font-size:13px;padding:0;"
                                        onclick="return confirm('Delete this post?');">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div style="padding:14px;">

                        <!-- Content box (image/video placeholder later) -->
                        <div style="border:1px solid #e5e7eb;border-radius:14px;padding:16px;background:#f9fafb;">
                            <div style="white-space:pre-wrap;line-height:1.45;">
                                {{ $post->caption }}
                            </div>
                        </div>

                        <!-- Hashtags parsed from caption -->
                        @php
                            preg_match_all('/#([A-Za-z0-9_]+)/', $post->caption ?? '', $m);
                            $tags = array_values(array_unique($m[1] ?? []));
                        @endphp

                        @if (count($tags) > 0)
                            <div style="margin-top:10px;display:flex;flex-wrap:wrap;gap:8px;">
                                @foreach ($tags as $t)
                                    <span style="font-size:12px;font-weight:900;color:#f97316;border:1px solid #fed7aa;background:#fff7ed;padding:6px 10px;border-radius:999px;">
                                        #{{ $t }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Comments preview (up to 3) -->
                        @php
                            $totalComments = $post->comments->count();
                            $commentsPreview = $post->comments->sortBy('created_at')->take(3);
                        @endphp

                        <div style="margin-top:12px;">
                            <div style="font-weight:900;margin-bottom:8px;">Comments</div>

                            @forelse ($commentsPreview as $c)
                                <div style="padding:10px 12px;border:1px solid #e5e7eb;border-radius:12px;margin-bottom:8px;background:#fff;">
                                    <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">
                                        <a href="{{ route('users.show', $c->user) }}"
                                           style="color:#111;font-weight:900;text-decoration:underline;">
                                            {{ $c->user->name }}
                                        </a>
                                        · {{ $c->created_at->diffForHumans() }}
                                    </div>
                                    <div style="line-height:1.35;">{{ $c->body }}</div>
                                </div>
                            @empty
                                <div style="color:#6b7280;">No comments yet.</div>
                            @endforelse

                            @if ($totalComments > 3)
                                <a href="{{ route('posts.show', $post) }}"
                                   style="display:inline-block;margin-top:6px;color:#f97316;font-weight:900;text-decoration:underline;">
                                    View more comments ({{ $totalComments - 3 }})
                                </a>
                            @else
                                <a href="{{ route('posts.show', $post) }}"
                                   style="display:inline-block;margin-top:6px;color:#111;font-weight:800;text-decoration:underline;">
                                    Open post
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:18px;color:#6b7280;">
                    No posts yet. Create the first post above.
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>




