<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminAuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('user')
            ->when($request->event, fn($q, $v) => $q->where('event', $v))
            ->when($request->type, fn($q, $v) => $q->where('auditable_type', 'App\\Models\\' . $v))
            ->latest()
            ->paginate(50);

        $events = AuditLog::select('event')->distinct()->pluck('event');
        $types = ['User', 'Election', 'Article', 'Event', 'Payment', 'Document', 'Booking'];

        return view('admin.audit-logs.index', compact('logs', 'events', 'types'));
    }
}
