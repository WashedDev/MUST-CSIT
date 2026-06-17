<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $search = $request->query('search');
        $from = $request->query('from');
        $to = $request->query('to');

        $user = auth()->user();
        $documents = Document::when($category, fn($q, $v) => $q->where('category', $v))
            ->when($search, fn($q, $v) => $q->where(function ($q) use ($v) {
                $q->where('title', 'like', "%{$v}%")
                  ->orWhereHas('uploader', fn($q) => $q->where('firstname', 'like', "%{$v}%")
                      ->orWhere('lastname', 'like', "%{$v}%"));
            }))
            ->when($from, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($to, fn($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->where(function ($q) use ($user) {
                $q->where('access_level', 'all');
                if ($user->isAdmin()) {
                    $q->orWhere('access_level', 'admin');
                }
                if (in_array($user->role, ['executive', 'admin'])) {
                    $q->orWhere('access_level', 'executive');
                }
            })
            ->latest()
            ->with('uploader')
            ->paginate(15);

        return view('documents.index', compact('documents', 'category', 'search', 'from', 'to'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:minutes,reports,constitution,financial,general',
            'version'      => 'nullable|string|max:20',
            'access_level' => 'required|in:all,executive,admin',
            'file'         => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:51200',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        Document::create([
            'title'        => $data['title'],
            'category'     => $data['category'],
            'version'      => $data['version'] ?? '1.0',
            'access_level' => $data['access_level'],
            'file_path'    => $path,
            'user_id'      => auth()->id(),
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document uploaded.');
    }

    public function download(Document $document)
    {
        if (! Storage::disk('public')->exists($document->file_path)) {
            return back()->withErrors(['file' => 'File not found.']);
        }

        return Storage::disk('public')->download($document->file_path);
    }

    public function preview(Document $document)
    {
        if (! Storage::disk('public')->exists($document->file_path)) {
            return back()->withErrors(['file' => 'File not found.']);
        }

        $url = Storage::disk('public')->url($document->file_path);
        $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));

        return view('documents.preview', compact('document', 'url', 'ext'));
    }
}
