@extends('layouts.dashboard')
@section('title', 'Documents — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Document Repository</h1>
    <p>Minutes, reports, constitution and other official records.</p>
  </div>
</div>

<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;align-items:center">
  <a href="{{ route('documents.index') }}" class="btn {{ !$category ? 'btn-primary' : 'btn-outline' }} btn-sm">All</a>
  <a href="{{ route('documents.index', ['category' => 'minutes']) }}" class="btn {{ $category === 'minutes' ? 'btn-primary' : 'btn-outline' }} btn-sm">Minutes</a>
  <a href="{{ route('documents.index', ['category' => 'reports']) }}" class="btn {{ $category === 'reports' ? 'btn-primary' : 'btn-outline' }} btn-sm">Reports</a>
  <a href="{{ route('documents.index', ['category' => 'constitution']) }}" class="btn {{ $category === 'constitution' ? 'btn-primary' : 'btn-outline' }} btn-sm">Constitution</a>
  <a href="{{ route('documents.index', ['category' => 'general']) }}" class="btn {{ $category === 'general' ? 'btn-primary' : 'btn-outline' }} btn-sm">General</a>
  <a href="{{ route('documents.create') }}" class="btn btn-accent btn-sm" style="margin-left:auto">Upload</a>
</div>

<div class="dash-card">
  @forelse($documents as $doc)
    <div class="dash-list-item">
      <div style="flex:1">
        <strong style="font-size:0.9rem">{{ $doc->title }}</strong>
        <div class="dash-list-meta">Uploaded by {{ $doc->uploader->name }} &middot; {{ $doc->created_at->format('M d, Y') }}</div>
      </div>
      <span class="tag">{{ ucfirst($doc->category) }}</span>
      <a href="{{ route('documents.download', $doc) }}" class="btn btn-primary btn-sm">Download</a>
    </div>
  @empty
    <p class="dash-empty">No documents uploaded yet.</p>
  @endforelse
</div>

<div style="margin-top:16px">{{ $documents->links() }}</div>

@endsection
