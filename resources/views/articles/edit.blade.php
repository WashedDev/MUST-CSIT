@extends('layouts.dashboard')
@section('title', 'Edit Article — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Edit Article</h1>
  </div>
</div>

<div class="dash-card" style="max-width:720px">
  <form method="POST" action="{{ route('articles.update', $article) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required>
      @error('title') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="type">Type</label>
      <select id="type" name="type" required>
        <option value="news" {{ (old('type', $article->type) === 'news') ? 'selected' : '' }}>News</option>
        <option value="tech" {{ (old('type', $article->type) === 'tech') ? 'selected' : '' }}>Tech Article</option>
        <option value="announcement" {{ (old('type', $article->type) === 'announcement') ? 'selected' : '' }}>Announcement</option>
        <option value="event" {{ (old('type', $article->type) === 'event') ? 'selected' : '' }}>Event</option>
      </select>
      @error('type') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="body">Body</label>
      <textarea id="body" name="body" rows="16" required>{{ old('body', $article->body) }}</textarea>
      @error('body') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div style="display:flex;gap:8px">
      <button class="btn btn-primary" type="submit" name="status" value="published">Publish</button>
      @if($article->status === 'draft')
        <button class="btn btn-outline" type="submit" name="status" value="draft">Keep as Draft</button>
      @endif
      <a href="{{ route('articles.show', $article) }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.7.2/tinymce.min.js" integrity="sha512-5LE8sD4SUQBNdMSAR4sSovMRxS9NOK+Uaw/6UeMF5yRs8J5aMH4g5l/rqVvGfV9XghIdNCjtQ1H0t0NCEc5VFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
tinymce.init({
  selector: '#body',
  plugins: 'advlist autolink lists link image charmap preview anchor pagebreak codesample table code fullscreen insertdatetime media table wordcount',
  toolbar: 'undo redo | blocks fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table code | fullscreen',
  menubar: false,
  branding: false,
  promotion: false,
  height: 400,
  content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, sans-serif; font-size: 16px; line-height: 1.6; padding: 12px; }'
});
</script>
@endpush

@endsection
