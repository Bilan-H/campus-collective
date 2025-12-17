<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Post</title></head>
<body style="font-family:system-ui;max-width:800px;margin:40px auto;">
  <a href="{{ route('feed.index') }}" style="color:#f97316;font-weight:900;text-decoration:underline;">← Back</a>

  <h1 style="margin-top:18px;">Edit Post</h1>

  <form method="POST" action="{{ route('posts.update', $post) }}">
    @csrf
    @method('PUT')

    <textarea name="caption" rows="6" style="width:100%;box-sizing:border-box;border:1px solid #d1d5db;border-radius:12px;padding:10px;">{{ old('caption', $post->caption) }}</textarea>
    @error('caption') <div style="color:#b91c1c;font-weight:900;margin-top:8px;">{{ $message }}</div> @enderror

    <button type="submit" style="margin-top:10px;border:none;border-radius:12px;padding:10px 14px;background:#f97316;color:#fff;font-weight:900;cursor:pointer;">
      Save changes
    </button>
  </form>
</body>
</html>
