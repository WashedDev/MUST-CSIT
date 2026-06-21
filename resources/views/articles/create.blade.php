@extends('layouts.dashboard')
@section('title', 'Write Article — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Write Article</h1>
  </div>
</div>

<div class="dash-card" style="max-width:720px">
  <form method="POST" action="{{ route('articles.store') }}">
    @csrf

    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="{{ old('title') }}" required>
      @error('title') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="type">Type</label>
      <select id="type" name="type" required>
        <option value="news" {{ old('type') === 'news' ? 'selected' : '' }}>News</option>
        <option value="tech" {{ old('type') === 'tech' ? 'selected' : '' }}>Tech Article</option>
        <option value="announcement" {{ old('type') === 'announcement' ? 'selected' : '' }}>Announcement</option>
        <option value="event" {{ old('type') === 'event' ? 'selected' : '' }}>Event</option>
      </select>
      @error('type') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="body">Body</label>
      <textarea id="body" name="body" rows="16" required>{{ old('body') }}</textarea>
      @error('body') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group" id="scheduleField" style="display:none">
      <label for="scheduled_at">Schedule Publication</label>
      <input type="datetime-local" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
      @error('scheduled_at') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div style="display:flex;gap:8px;flex-wrap:wrap">
      <button class="btn btn-primary" type="submit" name="status" value="published">Publish Now</button>
      <button class="btn btn-outline" type="button" id="scheduleBtn">Schedule...</button>
      <button class="btn btn-outline" type="submit" name="status" value="draft">Save as Draft</button>
      <a href="{{ route('articles.index') }}" class="btn btn-outline">Cancel</a>
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
<script>
document.getElementById('scheduleBtn')?.addEventListener('click', function() {
  var field = document.getElementById('scheduleField');
  field.style.display = field.style.display === 'none' ? 'block' : 'none';
});
</script>
@endpush

@endsection
