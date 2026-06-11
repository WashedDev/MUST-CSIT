@if($notifications->count())
  @foreach($notifications as $n)
    <div class="notif-item {{ $n->read ? '' : 'notif-item--unread' }}">
      @if($n->url)
        <a href="{{ route('notifications.read', $n) }}" class="notif-link">
          <div class="notif-title">{{ $n->title }}</div>
          @if($n->body)<div class="notif-body">{{ Str::limit($n->body, 80) }}</div>@endif
          <div class="notif-time">{{ $n->created_at->diffForHumans() }}</div>
        </a>
      @else
        <form method="POST" action="{{ route('notifications.read', $n) }}" class="notif-link-form">@csrf
          <button type="submit" class="notif-link" style="border:none;background:none;width:100%;text-align:left;cursor:pointer">
            <div class="notif-title">{{ $n->title }}</div>
            @if($n->body)<div class="notif-body">{{ Str::limit($n->body, 80) }}</div>@endif
            <div class="notif-time">{{ $n->created_at->diffForHumans() }}</div>
          </button>
        </form>
      @endif
    </div>
  @endforeach
  <div class="notif-footer">
    <a href="{{ route('notifications.index') }}">View all notifications</a>
    <form method="POST" action="{{ route('notifications.read-all') }}" style="display:inline">@csrf
      <button type="submit" style="border:none;background:none;color:var(--primary);cursor:pointer;font-size:0.78rem">Mark all read</button>
    </form>
  </div>
@else
  <div class="notif-empty">No notifications yet.</div>
@endif
