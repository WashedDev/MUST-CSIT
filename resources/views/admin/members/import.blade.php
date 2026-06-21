@extends('layouts.dashboard')
@section('title', 'Import Members — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Import Members</h1>
    <p>Upload a CSV file with member data to batch-import them.</p>
  </div>
</div>

<div class="dash-card" style="max-width:540px">
  <form method="POST" action="{{ route('admin.members.import.csv') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
      <label for="csv">CSV File</label>
      <input type="file" id="csv" name="csv" accept=".csv,.txt" required>
      @error('csv') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <p style="font-size:0.85rem;color:var(--ink-secondary);margin-bottom:16px">
      Required columns: <code>firstname</code>, <code>lastname</code>, <code>email</code>.<br>
      Optional: <code>reg_number</code>, <code>programme</code>, <code>year</code>.<br>
      First row must be the header row. Imported users must reset their password on first login.
    </p>

    <button class="btn btn-primary" type="submit">Import</button>
    <a href="{{ route('admin.members.index') }}" class="btn btn-outline">Cancel</a>
  </form>
</div>

@endsection
