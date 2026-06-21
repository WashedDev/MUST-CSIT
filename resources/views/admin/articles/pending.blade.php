@extends('layouts.dashboard')
@section('title', 'Pending Articles — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Pending Approval</h1>
    <p>{{ $articles->total() }} article(s) awaiting review.</p>
  </div>
</div>

<div class="dash-card" style="padding:0">
  <div class="dash-table-wrap">
    <table class="dash-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Author</th>
          <th>Type</th>
          <th>Submitted</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($articles as $article)
          <tr>
            <td><strong>{{ $article->title }}</strong></td>
            <td>{{ $article->author->name }}</td>
            <td><span class="tag">{{ ucfirst($article->type) }}</span></td>
            <td>{{ $article->created_at->format('M d, Y H:i') }}</td>
            <td class="cell-actions">
              <a href="{{ route('articles.show', $article) }}" class="btn btn-outline btn-sm">Preview</a>
              <form method="POST" action="{{ route('admin.articles.approve', $article) }}" style="display:inline">
                @csrf
                <button class="btn btn-primary btn-sm" onclick="return confirm('Approve this article?')">Approve</button>
              </form>
              <form method="POST" action="{{ route('admin.articles.reject', $article) }}" style="display:inline" onsubmit="return confirm('Reject this article?')">
                @csrf
                <button class="btn btn-danger btn-sm">Reject</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5"><p class="dash-empty">No articles pending approval.</p></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div style="margin-top:16px">{{ $articles->links() }}</div>

@endsection
