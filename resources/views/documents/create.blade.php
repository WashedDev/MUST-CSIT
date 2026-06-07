@extends('layouts.dashboard')
@section('title', 'Upload Document — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>Upload Document</h1>
</div>

<div class="dash-card" style="max-width:540px">
  <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" value="{{ old('title') }}" required>
      @error('title') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label>Category</label>
      <select name="category" required>
        <option value="minutes" {{ old('category') === 'minutes' ? 'selected' : '' }}>Minutes</option>
        <option value="reports" {{ old('category') === 'reports' ? 'selected' : '' }}>Reports</option>
        <option value="constitution" {{ old('category') === 'constitution' ? 'selected' : '' }}>Constitution</option>
        <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General</option>
      </select>
      @error('category') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label>File (max 10MB, PDF/Office docs)</label>
      <input type="file" name="file" required>
      @error('file') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <button class="btn btn-primary" type="submit">Upload</button>
    <a href="{{ route('documents.index') }}" class="btn btn-outline">Cancel</a>
  </form>
</div>

@endsection
