@extends('layouts.dashboard')
@section('title', $document->title . ' — Preview')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>{{ $document->title }}</h1>
    <p class="dash-sub">{{ ucfirst($document->category) }} &middot; uploaded {{ $document->created_at->format('M d, Y') }}</p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('documents.download', $document) }}" class="btn btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:6px"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Download
    </a>
    <a href="{{ route('documents.index') }}" class="btn btn-outline">&larr; Back</a>
  </div>
</div>

<div class="dash-card" style="padding:0;overflow:hidden;border-radius:var(--radius-md)">
@if(in_array($ext, ['pdf']))
  <iframe src="{{ $url }}" style="width:100%;height:85vh;border:none" title="{{ $document->title }}"></iframe>
@elseif(in_array($ext, ['doc', 'docx']))
  <div style="padding:48px 24px;text-align:center">
    <div style="font-size:3rem;margin-bottom:16px;opacity:0.3">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    </div>
    <h2>Word Document</h2>
    <p style="color:var(--ink-500);margin:8px 0 24px">In-browser preview is not available for {{ strtoupper($ext) }} files.</p>
    <a href="{{ route('documents.download', $document) }}" class="btn btn-primary">Download to View</a>
  </div>
@else
  <div style="padding:48px 24px;text-align:center">
    <div style="font-size:3rem;margin-bottom:16px;opacity:0.3">&#128196;</div>
    <h2>Preview Unavailable</h2>
    <p style="color:var(--ink-500);margin:8px 0 24px">Preview is not available for this file type.</p>
    <a href="{{ route('documents.download', $document) }}" class="btn btn-primary">Download to View</a>
  </div>
@endif
</div>

@endsection
