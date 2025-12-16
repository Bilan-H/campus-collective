<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login — Campus Collective</title>
</head>
<body>
    <div style="min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;font-family:system-ui;">
        
        <!-- Title -->
        <h1 style="
            margin-top:60px;
            margin-bottom:30px;
            font-size:32px;
            letter-spacing:1px;
            color:#f97316;
            text-align:center;
        ">
            CAMPUS COLLECTIVE ;)
        </h1>

        <!-- Login Card -->
        <div style="
            width:100%;
            max-width:420px;
            padding:28px;
            border:1px solid #ddd;
            border-radius:14px;
        ">

            @if ($errors->any())
                <div style="padding:12px;background:#fee;border:1px solid #fca5a5;margin-bottom:16px;">
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
                       style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;margin:6px 0 16px;"><br>

                <label>Password</label><br>
                <input type="password" name="password" required
                       style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;margin:6px 0 16px;"><br>

                <label style="display:flex;gap:8px;align-items:center;margin-bottom:18px;">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <button type="submit"
                        style="
                            width:100%;
                            padding:12px;
                            border:none;
                            border-radius:10px;
                            background:#f97316;
                            color:#fff;
                            font-weight:600;
                            cursor:pointer;
                        ">
                    Log in
                </button>
            </form>

            <p style="margin-top:18px;text-align:center;">
                No account?
                <a href="{{ route('register') }}" style="color:#f97316;font-weight:600;">
                    Register
                </a>
            </p>
        </div>
    </div>
</body>
</html>


