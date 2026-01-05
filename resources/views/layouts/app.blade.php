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

    body {
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .cc-orange { color: var(--cc-orange) !important; }

    .btn-cc {
      background: var(--cc-orange);
      border-color: var(--cc-orange);
      color:#fff;
      font-weight:800;
    }

    .btn-cc:hover {
      filter: brightness(0.95);
      color:#fff;
    }

    .card-soft {
      border:1px solid #e5e7eb;
      border-radius:16px;
    }

    .fw-black {
      font-weight:900;
    }
  </style>
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white border-bottom">
  <div class="container">

    <!-- Brand -->
    <a class="navbar-brand fw-black cc-orange"
       href="{{ route('feed.index') }}"
       style="letter-spacing:.5px;">
      CAMPUS COLLECTIVE ;)
    </a>

    <!-- Right side -->
    <div class="d-flex align-items-center gap-3 ms-auto">

      <!-- Logged in user -->
      <span class="text-secondary small d-none d-md-inline">
        Logged in as <strong>{{ auth()->user()->name }}</strong>
      </span>

      <!-- Nav links -->
      <a class="text-decoration-none cc-orange fw-bold" href="{{ route('feed.index') }}">
        Feed
      </a>

      <a class="text-decoration-none cc-orange fw-bold" href="{{ route('profile.edit') }}">
        Profile
      </a>

      {{-- Notifications --}}
      @php
        $unreadCount = auth()->user()->unreadNotifications()->count();
      @endphp

      <a class="text-decoration-none cc-orange fw-bold position-relative"
         href="{{ route('notifications.index') }}">
        Notifications
        @if ($unreadCount > 0)
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
            {{ $unreadCount }}
          </span>
        @endif
      </a>

      <!-- Logout -->
      <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button
          type="submit"
          class="btn btn-link p-0 cc-orange fw-bold text-decoration-none">
          Logout
        </button>
      </form>

    </div>
  </div>
</nav>

<main class="container py-4" style="max-width:900px;">

  {{-- Flash success --}}
  @if (session('success'))
    <div class="alert alert-success card-soft mb-4">
      {{ session('success') }}
    </div>
  @endif

  @yield('content')

</main>

@stack('scripts')

</body>
</html>