@extends('layouts.dashboard')
@section('title', 'Write Article — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Write Article</h1>
  </div>
</div>

<div class="dash-card" style="max-width:640px">
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
      </select>
      @error('type') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="body">Body</label>
      <textarea id="body" name="body" rows="10" required>{{ old('body') }}</textarea>
      @error('body') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div style="display:flex;gap:8px">
      <button class="btn btn-primary" type="submit">Publish</button>
      <a href="{{ route('articles.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
