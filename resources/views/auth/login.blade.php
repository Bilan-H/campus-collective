<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login — Campus Collective</title>
</head>
<body>
    <div style="max-width:420px;margin:60px auto;font-family:system-ui;">
        <h1 style="font-size:28px;margin-bottom:8px;">Campus Collective</h1>
        <p style="color:#555;margin-top:0;margin-bottom:24px;">Log in to view the feed and post.</p>

        @if ($errors->any())
            <div style="padding:12px;background:#fee;border:1px solid #f99;margin-bottom:16px;">
                <strong>Fix this:</strong>
                <ul style="margin:8px 0 0 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;margin:6px 0 14px;"><br>

            <label>Password</label><br>
            <input type="password" name="password" required
                   style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;margin:6px 0 14px;"><br>

            <label style="display:flex;gap:8px;align-items:center;margin-bottom:16px;">
                <input type="checkbox" name="remember">
                Remember me
            </label>

            <button type="submit"
                    style="width:100%;padding:12px;border:none;border-radius:10px;background:#111;color:#fff;">
                Log in
            </button>
        </form>

        <p style="margin-top:16px;">
            No account? <a href="{{ route('register') }}">Register</a>
        </p>
    </div>
</body>
</html>

