@extends('layouts.app')
@section('title', 'Documents — MUST CSIT Society')
@section('content')

<div class="page-head">
  <div class="container">
    <h1>Document Repository</h1>
    <p>Minutes, reports, constitution and other official records.</p>
  </div>
</div>

<section class="block">
  <div class="container">
    <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap">
      <a href="{{ route('documents.index') }}" class="btn {{ !$category ? 'btn-primary' : 'btn-outline' }}">All</a>
      <a href="{{ route('documents.index', ['category' => 'minutes']) }}" class="btn {{ $category === 'minutes' ? 'btn-primary' : 'btn-outline' }}">Minutes</a>
      <a href="{{ route('documents.index', ['category' => 'reports']) }}" class="btn {{ $category === 'reports' ? 'btn-primary' : 'btn-outline' }}">Reports</a>
      <a href="{{ route('documents.index', ['category' => 'constitution']) }}" class="btn {{ $category === 'constitution' ? 'btn-primary' : 'btn-outline' }}">Constitution</a>
      <a href="{{ route('documents.index', ['category' => 'general']) }}" class="btn {{ $category === 'general' ? 'btn-primary' : 'btn-outline' }}">General</a>
      <a href="{{ route('documents.create') }}" class="btn btn-gold" style="margin-left:auto">Upload</a>
    </div>

    @forelse($documents as $doc)
      <div class="event" style="flex-direction:column;align-items:start;gap:4px">
        <div style="display:flex;justify-content:space-between;width:100%">
          <strong>{{ $doc->title }}</strong>
          <span class="tag">{{ ucfirst($doc->category) }}</span>
        </div>
        <div style="color:var(--muted);font-size:.85rem;display:flex;justify-content:space-between;width:100%">
          <span>Uploaded by {{ $doc->uploader->name }} · {{ $doc->created_at->format('M d, Y') }}</span>
          <a href="{{ route('documents.download', $doc) }}" class="btn btn-primary" style="padding:4px 12px;font-size:.85rem">Download</a>
        </div>
      </div>
    @empty
      <p style="color:var(--muted)">No documents uploaded yet.</p>
    @endforelse

    {{ $documents->links() }}
  </div>
</section>

@endsection
