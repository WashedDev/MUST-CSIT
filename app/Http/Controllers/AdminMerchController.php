<?php

namespace App\Http\Controllers;

use App\Models\MerchItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMerchController extends Controller
{
    public function index()
    {
        $items = MerchItem::latest()->paginate(20);
        return view('admin.merch.index', compact('items'));
    }

    public function create()
    {
        return view('admin.merch.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('merch', 'public');
        }

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $img) {
                $paths[] = $img->store('merch', 'public');
            }
            $data['images'] = $paths;
        }

        $data['is_active'] = $request->boolean('is_active', true);

        MerchItem::create($data);

        return redirect()->route('admin.merch.index')
            ->with('success', 'Merch item created.');
    }

    public function edit(MerchItem $merchItem)
    {
        return view('admin.merch.edit', compact('merchItem'));
    }

    public function update(Request $request, MerchItem $merchItem)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($merchItem->image) {
                Storage::disk('public')->delete($merchItem->image);
            }
            $data['image'] = $request->file('image')->store('merch', 'public');
        } else {
            unset($data['image']);
        }

        if ($request->hasFile('images')) {
            if ($merchItem->images) {
                foreach ($merchItem->images as $old) {
                    Storage::disk('public')->delete($old);
                }
            }
            $paths = [];
            foreach ($request->file('images') as $img) {
                $paths[] = $img->store('merch', 'public');
            }
            $data['images'] = $paths;
        } else {
            unset($data['images']);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $merchItem->update($data);

        return redirect()->route('admin.merch.index')
            ->with('success', 'Merch item updated.');
    }

    public function destroy(MerchItem $merchItem)
    {
        if ($merchItem->image) {
            Storage::disk('public')->delete($merchItem->image);
        }

        if ($merchItem->images) {
            foreach ($merchItem->images as $old) {
                Storage::disk('public')->delete($old);
            }
        }

        $merchItem->delete();

        return redirect()->route('admin.merch.index')
            ->with('success', 'Merch item deleted.');
    }
}
