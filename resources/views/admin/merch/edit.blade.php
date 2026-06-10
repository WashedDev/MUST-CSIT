@extends('layouts.dashboard')
@section('title', 'Edit Merch Item — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Edit &mdash; {{ $merchItem->name }}</h1>
  </div>
</div>

<div class="dash-card" style="max-width:640px">
  <form method="POST" action="{{ route('admin.merch.update', $merchItem) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" value="{{ old('name', $merchItem->name) }}" required>
      @error('name') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4">{{ old('description', $merchItem->description) }}</textarea>
      @error('description') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-grid" style="grid-template-columns:1fr 1fr">
      <div class="form-group">
        <label for="price">Price (MWK)</label>
        <input type="number" id="price" name="price" value="{{ old('price', $merchItem->price) }}" min="0" step="0.01" required>
        @error('price') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label for="stock">Stock</label>
        <input type="number" id="stock" name="stock" value="{{ old('stock', $merchItem->stock) }}" min="0" required>
        @error('stock') <span class="form-error">{{ $message }}</span> @enderror
      </div>
    </div>

    <div class="form-group">
      <label for="image">Image</label>
      @if($merchItem->image)
        <div style="margin-bottom:8px">
          <img src="{{ $merchItem->imageUrl() }}" alt="" style="width:96px;height:96px;object-fit:cover;border-radius:var(--radius-sm)">
        </div>
      @endif
      <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
      @error('image') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label class="checkbox-label">
        <input type="checkbox" name="is_active" value="1" @checked($merchItem->is_active)>
        <span>Active (visible to members)</span>
      </label>
    </div>

    <div style="display:flex;gap:8px">
      <button class="btn btn-primary" type="submit">Update Item</button>
      <a href="{{ route('admin.merch.index') }}" class="btn btn-outline">Cancel</a>
    </div>
  </form>
</div>

@endsection
