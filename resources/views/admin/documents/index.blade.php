@extends('layouts.dashboard')
@section('title', 'Manage Documents — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Manage Documents</h1>
    <p>{{ $documents->total() }} document(s).</p>
  </div>
</div>

<div class="dash-card" style="padding:0">
  <div class="dash-table-wrap">
    <table class="dash-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Version</th>
          <th>Access</th>
          <th>Uploaded By</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($documents as $doc)
          <tr>
            <td><strong>{{ $doc->title }}</strong></td>
            <td>{{ ucfirst($doc->category) }}</td>
            <td>{{ $doc->version ?? '1.0' }}</td>
            <td>{{ $doc->access_level === 'all' ? 'Public' : ucfirst($doc->access_level) }}</td>
            <td>{{ $doc->uploader->name }}</td>
            <td>{{ $doc->created_at->format('M d, Y') }}</td>
            <td class="cell-actions">
              <a href="{{ route('documents.download', $doc) }}" class="btn btn-primary btn-sm">Download</a>
              <form method="POST" action="{{ route('admin.documents.destroy', $doc) }}" style="display:inline" onsubmit="return confirm('Delete this document?')">
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

<div style="margin-top:16px">{{ $documents->links() }}</div>

@endsection
