@extends('layouts.app')
@section('title', 'Manage Members — MUST CSIT Society')
@section('content')

<div class="page-head">
  <div class="container">
    <h1>Manage Members</h1>
    <p>{{ $members->total() }} registered member(s).</p>
  </div>
</div>

<section class="block">
  <div class="container">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table style="width:100%;border-collapse:collapse">
      <thead>
        <tr style="text-align:left;border-bottom:2px solid #e5e7eb">
          <th style="padding:8px">Name</th>
          <th style="padding:8px">Email</th>
          <th style="padding:8px">Reg #</th>
          <th style="padding:8px">Programme</th>
          <th style="padding:8px">Year</th>
          <th style="padding:8px">Joined</th>
          <th style="padding:8px">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($members as $m)
          <tr style="border-bottom:1px solid #e5e7eb">
            <td style="padding:8px">{{ $m->name }}</td>
            <td style="padding:8px">{{ $m->email }}</td>
            <td style="padding:8px">{{ $m->reg_number ?? '—' }}</td>
            <td style="padding:8px">{{ $m->programme ?? '—' }}</td>
            <td style="padding:8px">{{ $m->year ?? '—' }}</td>
            <td style="padding:8px">{{ $m->created_at->format('M d, Y') }}</td>
            <td style="padding:8px">
              <a href="{{ route('admin.members.edit', $m) }}" class="btn btn-primary" style="padding:4px 10px;font-size:.85rem">Edit</a>
              <form method="POST" action="{{ route('admin.members.destroy', $m) }}" style="display:inline" onsubmit="return confirm('Remove this member?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline" style="padding:4px 10px;font-size:.85rem">Remove</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{ $members->links() }}
  </div>
</section>

@endsection
