@extends('layouts.dashboard')
@section('title', 'Upload Document — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Upload Document</h1>
  </div>
</div>

<div class="dash-card" style="max-width:540px">
  <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="{{ old('title') }}" required>
      @error('title') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="category">Category</label>
      <select id="category" name="category" required>
        <option value="minutes" {{ old('category') === 'minutes' ? 'selected' : '' }}>Minutes</option>
        <option value="reports" {{ old('category') === 'reports' ? 'selected' : '' }}>Reports</option>
        <option value="constitution" {{ old('category') === 'constitution' ? 'selected' : '' }}>Constitution</option>
        <option value="financial" {{ old('category') === 'financial' ? 'selected' : '' }}>Financial Records</option>
        <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General</option>
      </select>
      @error('category') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="version">Version</label>
      <input type="text" id="version" name="version" value="{{ old('version', '1.0') }}" maxlength="20">
      @error('version') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="access_level">Access Level</label>
      <select id="access_level" name="access_level" required>
        <option value="all" {{ old('access_level') === 'all' ? 'selected' : '' }}>All Members</option>
        <option value="executive" {{ old('access_level') === 'executive' ? 'selected' : '' }}>Executive Only</option>
        <option value="admin" {{ old('access_level') === 'admin' ? 'selected' : '' }}>Admin Only</option>
      </select>
      @error('access_level') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
      <label for="file">File (max 50MB, PDF/Office docs)</label>
      <input type="file" id="file" name="file" required>
      @error('file') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div style="display:flex;gap:8px">
      <button class="btn btn-primary" type="submit">Upload</button>
      <a href="{{ route('documents.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
