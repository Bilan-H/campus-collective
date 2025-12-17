<!doctype html>
<html>
<head><meta charset="utf-8"><title>Post</title></head>
<body>
  <div style="max-width:720px;margin:40px auto;font-family:system-ui;">
    <a href="{{ route('feed.index') }}">← Back</a>

    <h2>{{ $post->user->name }}</h2>
    <p>{{ $post->caption }}</p>

    <hr>

    <h3>Comments ({{ $post->comments->count() }})</h3>

    @foreach ($post->comments as $c)
      <div style="padding:10px;border:1px solid #ddd;border-radius:10px;margin-bottom:10px;">
        <strong>
          <a href="{{ route('users.show', $c->user) }}">{{ $c->user->name }}</a>
        </strong>
        <div>{{ $c->body }}</div>
      </div>
    @endforeach

    <form method="POST" action="{{ route('comments.store', $post) }}">
      @csrf
      <textarea name="body" rows="3" required style="width:100%;"></textarea>
      <button type="submit">Comment</button>
    </form>
  </div>
</body>
</html>


