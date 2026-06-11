@extends('layouts.dashboard')
@section('title', 'Manage Merch — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Manage Merch</h1>
    <p>{{ $items->total() }} item(s).</p>
  </div>
  <a href="{{ route('admin.merch.create') }}" class="btn btn-primary">Add Item</a>
</div>

<div class="dash-card" style="padding:0">
  <div class="dash-table-wrap">
    <table class="dash-table">
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $item)
          <tr>
            <td>
              @if($item->image)
                <img src="{{ $item->imageUrl() }}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:var(--radius-sm)">
              @else
                <span style="display:inline-block;width:48px;height:48px;background:var(--surface-alt);border-radius:var(--radius-sm)"></span>
              @endif
            </td>
            <td><strong>{{ $item->name }}</strong></td>
            <td>MWK {{ number_format((float) $item->price) }}</td>
            <td>{{ $item->stock }}</td>
            <td>
              @if($item->is_active)
                <span class="tag" style="background:#dcfce7;color:#16A34A">Active</span>
              @else
                <span class="tag" style="background:#fef2f2;color:#DC2626">Inactive</span>
              @endif
            </td>
            <td class="cell-actions">
              <a href="{{ route('admin.merch.edit', $item) }}" class="btn btn-primary btn-sm">Edit</a>
              <form method="POST" action="{{ route('admin.merch.destroy', $item) }}" style="display:inline" onsubmit="return confirm('Delete this item?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div style="margin-top:16px">{{ $items->links() }}</div>

@endsection
