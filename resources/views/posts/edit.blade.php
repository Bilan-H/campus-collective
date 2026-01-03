<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Post — Campus Collective</title>
</head>
<body style="margin:0;font-family:system-ui;background:#fafafa;">
<div style="max-width:900px;margin:0 auto;padding:22px 18px;">

  <a href="{{ route('posts.show', $post) }}"
     style="color:#f97316;font-weight:900;text-decoration:underline;">← Back to Post</a>

  <h1 style="margin:14px 0;color:#f97316;letter-spacing:1px;">EDIT POST</h1>

  @if (session('success'))
    <div style="padding:10px;border-radius:10px;background:#ecfdf5;border:1px solid #bbf7d0;margin-bottom:12px;">
      {{ session('success') }}
    </div>
  @endif

  {{-- Update form --}}
  <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;">
    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <label for="caption" style="display:block;font-weight:900;margin-bottom:8px;">
        Caption
      </label>

      <textarea id="caption" name="caption" rows="5" required
        style="width:100%;box-sizing:border-box;border:1px solid #d1d5db;border-radius:12px;padding:10px;resize:vertical;"
      >{{ old('caption', $post->caption) }}</textarea>

      @error('caption')
        <div style="margin-top:8px;color:#b91c1c;font-size:13px;font-weight:800;">{{ $message }}</div>
      @enderror

      <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:10px;">
        <a href="{{ route('posts.show', $post) }}"
           style="display:inline-block;padding:10px 14px;border-radius:12px;border:1px solid #e5e7eb;text-decoration:none;color:#111;font-weight:900;">
          Cancel
        </a>
      @if ($post->image_path)
  <div style="margin-top:12px;">
    <div style="font-weight:900;margin-bottom:8px;">Current image</div>
    <img src="{{ asset('storage/'.$post->image_path) }}"
         style="max-width:100%;border-radius:12px;border:1px solid #e5e7eb;">
  </div>
@endif

<div style="margin-top:12px;">
  <label style="display:block;font-weight:900;margin-bottom:8px;">
    Replace image (optional)
  </label>
  <input type="file" name="image" accept="image/*">
  @error('image')
    <div style="margin-top:8px;color:#b91c1c;font-size:13px;font-weight:800;">{{ $message }}</div>
  @enderror
</div>

        <button type="submit"
          style="border:none;border-radius:12px;padding:10px 14px;background:#f97316;color:#fff;font-weight:900;cursor:pointer;">
          Save changes
        </button>
      </div>
    </form>
  </div>

  {{-- Delete (still allowed on edit page) --}}
  <div style="margin-top:14px;background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px;">
    <div style="font-weight:900;color:#b91c1c;margin-bottom:10px;">Danger zone</div>

    <form method="POST"
          action="{{ route('posts.destroy', $post) }}"
          onsubmit="return confirm('Delete this post? This cannot be undone.');">
      @csrf
      @method('DELETE')
      <button type="submit"
        style="background:#b91c1c;color:#fff;border:none;border-radius:12px;padding:10px 14px;font-weight:900;cursor:pointer;">
        Delete post
      </button>
    </form>
  </div>

</div>
</body>
</html>

