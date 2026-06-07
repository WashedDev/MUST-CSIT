@extends('layouts.app')
@section('title', 'Write Article — MUST CSIT Society')
@section('content')

<div class="page-head">
  <div class="container">
    <h1>Write Article</h1>
  </div>
</div>

<section class="block">
  <div class="container">
    <form method="POST" action="{{ route('articles.store') }}" style="max-width:600px">
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
</section>

@endsection
