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
        $documents = Document::when($category, fn($q, $v) => $q->where('category', $v))
            ->latest()
            ->with('uploader')
            ->paginate(15);

        return view('documents.index', compact('documents', 'category'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|in:minutes,reports,constitution,general',
            'file'     => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:10240',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        Document::create([
            'title'     => $data['title'],
            'category'  => $data['category'],
            'file_path' => $path,
            'user_id'   => auth()->id(),
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
}
