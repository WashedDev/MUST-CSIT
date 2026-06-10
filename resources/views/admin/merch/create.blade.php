@extends('layouts.dashboard')
@section('title', 'Add Merch Item — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Add Merch Item</h1>
  </div>
</div>

<div class="dash-card" style="max-width:640px">
  <form method="POST" action="{{ route('admin.merch.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" value="{{ old('name') }}" required>
      @error('name') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
      @error('description') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-grid" style="grid-template-columns:1fr 1fr">
      <div class="form-group">
        <label for="price">Price (MWK)</label>
        <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
        @error('price') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label for="stock">Stock</label>
        <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
        @error('stock') <span class="form-error">{{ $message }}</span> @enderror
      </div>
    </div>

    <div class="form-group">
      <label for="image">Image</label>
      <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
      @error('image') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label class="checkbox-label">
        <input type="checkbox" name="is_active" value="1" checked>
        <span>Active (visible to members)</span>
      </label>
    </div>

    <div style="display:flex;gap:8px">
      <button class="btn btn-primary" type="submit">Add Item</button>
      <a href="{{ route('admin.merch.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
