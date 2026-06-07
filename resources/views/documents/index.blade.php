@extends('layouts.dashboard')
@section('title', 'Documents — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>Document Repository</h1>
  <p>Minutes, reports, constitution and other official records.</p>
</div>

<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap">
  <a href="{{ route('documents.index') }}" class="btn {{ !$category ? 'btn-primary' : 'btn-outline' }}">All</a>
  <a href="{{ route('documents.index', ['category' => 'minutes']) }}" class="btn {{ $category === 'minutes' ? 'btn-primary' : 'btn-outline' }}">Minutes</a>
  <a href="{{ route('documents.index', ['category' => 'reports']) }}" class="btn {{ $category === 'reports' ? 'btn-primary' : 'btn-outline' }}">Reports</a>
  <a href="{{ route('documents.index', ['category' => 'constitution']) }}" class="btn {{ $category === 'constitution' ? 'btn-primary' : 'btn-outline' }}">Constitution</a>
  <a href="{{ route('documents.index', ['category' => 'general']) }}" class="btn {{ $category === 'general' ? 'btn-primary' : 'btn-outline' }}">General</a>
  <a href="{{ route('documents.create') }}" class="btn btn-gold" style="margin-left:auto">Upload</a>
</div>

<div class="dash-card">
  @forelse($documents as $doc)
    <div class="dash-list-item">
      <div style="flex:1">
        <strong>{{ $doc->title }}</strong>
        <div class="dash-list-meta">Uploaded by {{ $doc->uploader->name }} · {{ $doc->created_at->format('M d, Y') }}</div>
      </div>
      <span class="tag">{{ ucfirst($doc->category) }}</span>
      <a href="{{ route('documents.download', $doc) }}" class="btn btn-primary" style="padding:6px 14px;font-size:.85rem">Download</a>
    </div>
  @empty
    <p class="dash-empty">No documents uploaded yet.</p>
  @endforelse
</div>

<div style="margin-top:16px">{{ $documents->links() }}</div>

@endsection
