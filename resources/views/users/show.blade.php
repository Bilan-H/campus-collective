<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Profile</title></head>
<body style="font-family:system-ui;max-width:720px;margin:40px auto;">
    <a href="{{ route('feed.index') }}">← Back</a>

    <h1>Edit Profile</h1>

    @if (session('success'))
        <div style="padding:10px;background:#ecfdf5;border:1px solid #bbf7d0;border-radius:10px;margin-bottom:12px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <label>Name</label><br>
        <input name="name" value="{{ old('name', $user->name) }}"
               style="width:100%;padding:10px;border:1px solid #ccc;border-radius:10px;margin:6px 0 12px;" />
        @error('name') <div style="color:#b91c1c;">{{ $message }}</div> @enderror

        <label>Email</label><br>
        <input name="email" value="{{ old('email', $user->email) }}"
               style="width:100%;padding:10px;border:1px solid #ccc;border-radius:10px;margin:6px 0 12px;" />
        @error('email') <div style="color:#b91c1c;">{{ $message }}</div> @enderror

        <button type="submit"
                style="background:#f97316;color:#fff;border:none;border-radius:10px;padding:10px 14px;font-weight:800;cursor:pointer;">
            Save
        </button>
    </form>

    <hr style="margin:18px 0;">

    <form method="POST" action="{{ route('profile.destroy') }}">
        @csrf
        @method('DELETE')
        <button type="submit"
                style="background:#b91c1c;color:#fff;border:none;border-radius:10px;padding:10px 14px;font-weight:800;cursor:pointer;">
            Delete account
        </button>
    </form>
</body>
</html>





