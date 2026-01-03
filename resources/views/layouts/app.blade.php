<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Campus Collective')</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root { --cc-orange:#f97316; }
    .cc-orange { color: var(--cc-orange) !important; }
    .btn-cc { background: var(--cc-orange); border-color: var(--cc-orange); color:#fff; font-weight:800; }
    .btn-cc:hover { filter: brightness(0.95); }
    .card-soft { border:1px solid #e5e7eb; border-radius: 16px; }
  </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand fw-black cc-orange" href="{{ route('feed.index') }}" style="font-weight:900;letter-spacing:.5px;">
      CAMPUS COLLECTIVE ;)
    </a>

    <div class="d-flex align-items-center gap-3 ms-auto">
      <span class="text-secondary small">
        Logged in as <strong>{{ auth()->user()->name }}</strong>
      </span>

      <a class="text-decoration-none cc-orange fw-bold" href="{{ route('feed.index') }}">Feed</a>
      <a class="text-decoration-none cc-orange fw-bold" href="{{ route('profile.edit') }}">Profile</a>

      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button class="btn btn-link p-0 cc-orange fw-bold text-decoration-none" type="submit">Logout</button>
      </form>
    </div>
  </div>
</nav>

<main class="container py-4" style="max-width: 900px;">
  @if (session('success'))
    <div class="alert alert-success card-soft">{{ session('success') }}</div>
  @endif

  @yield('content')
</main>

</body>
</html>

