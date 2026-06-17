@extends('layouts.dashboard')
@section('title', 'Documents — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Document Repository</h1>
    <p>Minutes, reports, constitution and other official records.</p>
  </div>
</div>

<div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;align-items:center">
  <a href="{{ route('documents.index') }}" class="btn {{ !$category ? 'btn-primary' : 'btn-outline' }} btn-sm">All</a>
  <a href="{{ route('documents.index', ['category' => 'minutes']) }}" class="btn {{ $category === 'minutes' ? 'btn-primary' : 'btn-outline' }} btn-sm">Minutes</a>
  <a href="{{ route('documents.index', ['category' => 'reports']) }}" class="btn {{ $category === 'reports' ? 'btn-primary' : 'btn-outline' }} btn-sm">Reports</a>
  <a href="{{ route('documents.index', ['category' => 'constitution']) }}" class="btn {{ $category === 'constitution' ? 'btn-primary' : 'btn-outline' }} btn-sm">Constitution</a>
  <a href="{{ route('documents.index', ['category' => 'financial']) }}" class="btn {{ $category === 'financial' ? 'btn-primary' : 'btn-outline' }} btn-sm">Financial</a>
  <a href="{{ route('documents.index', ['category' => 'general']) }}" class="btn {{ $category === 'general' ? 'btn-primary' : 'btn-outline' }} btn-sm">General</a>
  <a href="{{ route('documents.create') }}" class="btn btn-accent btn-sm" style="margin-left:auto">Upload</a>
</div>

<form method="GET" style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;align-items:center">
  @if($category)<input type="hidden" name="category" value="{{ $category }}">@endif
  <input type="text" name="search" class="form-control" style="width:200px" placeholder="Search by title or uploader..." value="{{ $search ?? '' }}">
  <input type="date" name="from" class="form-control" style="width:auto" value="{{ $from ?? '' }}">
  <input type="date" name="to" class="form-control" style="width:auto" value="{{ $to ?? '' }}">
  <button class="btn btn-primary btn-sm" type="submit">Filter</button>
  @if($search || $from || $to)
    <a href="{{ route('documents.index', $category ? ['category' => $category] : []) }}" class="btn btn-outline btn-sm">Clear</a>
  @endif
</form>

<div class="dash-card">
  @forelse($documents as $doc)
    <div class="dash-list-item">
      <div style="flex:1">
        <strong style="font-size:0.9rem">{{ $doc->title }}</strong>
        <div class="dash-list-meta">Uploaded by {{ $doc->uploader->name }} &middot; v{{ $doc->version ?? '1.0' }} &middot; {{ $doc->created_at->format('M d, Y') }}</div>
      </div>
      <span class="tag">{{ ucfirst($doc->category) }}</span>
      <span class="tag" style="background:var(--surface-alt);color:var(--ink-secondary)">{{ $doc->access_level === 'all' ? 'Public' : ucfirst($doc->access_level) }}</span>
      @php $ext = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION)); @endphp
      @if(in_array($ext, ['pdf', 'doc', 'docx']))
        <a href="{{ route('documents.preview', $doc) }}" class="btn btn-outline btn-sm">Preview</a>
      @endif
      <a href="{{ route('documents.download', $doc) }}" class="btn btn-primary btn-sm">Download</a>
    </div>
  @empty
    <p class="dash-empty">No documents uploaded yet.</p>
  @endforelse
</div>

<div style="margin-top:16px">{{ $documents->links() }}</div>

@endsection
