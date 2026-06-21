@extends('layouts.dashboard')
@section('title', 'Create Poll — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Create Opinion Poll</h1>
    <p>Create a non-binding opinion poll for members.</p>
  </div>
</div>

<form method="POST" action="{{ route('admin.polls.store') }}" id="pollForm">
  @csrf

  <div class="dash-card" style="max-width:600px">
    <div style="display:flex;flex-direction:column;gap:16px">
      <div>
        <label class="label" for="question">Question</label>
        <input class="input" id="question" name="question" value="{{ old('question') }}" required>
        @error('question') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="label" for="description">Description (optional)</label>
        <textarea class="input" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        @error('description') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="label" for="ends_at">Ends at (optional)</label>
        <input class="input" id="ends_at" name="ends_at" type="datetime-local" value="{{ old('ends_at') }}">
        @error('ends_at') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="label">Options</label>
        <div id="optionsContainer">
          @if(old('options'))
            @foreach(old('options') as $i => $opt)
              <div class="option-row" style="display:flex;gap:8px;margin-bottom:8px">
                <input class="input" name="options[]" value="{{ $opt }}" required style="flex:1">
                <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">×</button>
              </div>
            @endforeach
          @else
            <div class="option-row" style="display:flex;gap:8px;margin-bottom:8px">
              <input class="input" name="options[]" required style="flex:1" placeholder="Option 1">
              <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">×</button>
            </div>
            <div class="option-row" style="display:flex;gap:8px;margin-bottom:8px">
              <input class="input" name="options[]" required style="flex:1" placeholder="Option 2">
              <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">×</button>
            </div>
          @endif
        </div>
        <button type="button" class="btn btn-outline btn-sm" onclick="addOption()">+ Add Option</button>
        @error('options') <br><span class="field-error">{{ $message }}</span> @enderror
      </div>

      <button class="btn btn-primary" type="submit">Create Poll</button>
    </div>
  </div>
</form>

<p style="margin-top:24px"><a href="{{ route('polls.index') }}">&larr; Back to polls</a></p>

@push('scripts')
<script>
function addOption() {
  var container = document.getElementById('optionsContainer');
  var row = document.createElement('div');
  row.className = 'option-row';
  row.style.cssText = 'display:flex;gap:8px;margin-bottom:8px';
  row.innerHTML = '<input class="input" name="options[]" required style="flex:1" placeholder="Option ' + (container.children.length + 1) + '">' +
    '<button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">×</button>';
  container.appendChild(row);
}
</script>
@endpush

@endsection
