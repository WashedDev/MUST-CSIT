@extends('layouts.dashboard')
@section('title', 'Write Article — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>Write Article</h1>
</div>

<div class="dash-card" style="max-width:640px">
  <form method="POST" action="{{ route('articles.store') }}">
    @csrf

    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" value="{{ old('title') }}" required>
      @error('title') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label>Type</label>
      <select name="type" required>
        <option value="news" {{ old('type') === 'news' ? 'selected' : '' }}>News</option>
        <option value="tech" {{ old('type') === 'tech' ? 'selected' : '' }}>Tech Article</option>
      </select>
      @error('type') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label>Body</label>
      <textarea name="body" rows="10" required>{{ old('body') }}</textarea>
      @error('body') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <button class="btn btn-primary" type="submit">Publish</button>
    <a href="{{ route('articles.index') }}" class="btn btn-outline">Cancel</a>
  </form>
</div>

@endsection
