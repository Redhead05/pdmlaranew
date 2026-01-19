<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::with('category')->orderByDesc('created_at')->paginate(20);
        $categories = Category::whereIn('name', ['photo', 'video'])->orderBy('name')->get();
        return view('menu.adminlanding.gallery.index', compact('galleries','categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereIn('name', ['photo', 'video'])->orderBy('name')->get();
        return view('menu.adminlanding.gallery.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm|max:51200', //50mb
            'external_url' => 'nullable|url|max:2000',
            'description' => 'required|string',
            'is_active' => 'nullable|in:0,1',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // jika external_url ada, gunakan sebagai image (url eksternal) dan jangan menyimpan file
        $slug = Str::slug($validated['title']) . '-' . time();
        $path = null;

        if (!empty($validated['external_url'])) {
            // simpan URL eksternal apa adanya
            $path = $validated['external_url'];
        } elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('galleries', 'public');
        }

        Gallery::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'image' => $path,
            'description' => $validated['description'] ?? null,
            'is_active' => isset($validated['is_active']) ? (bool) $validated['is_active'] : true,
            'category_id' => $validated['category_id'] ?? null,
        ]);

        return redirect()->route('adminlanding.gallery.index')->with('success', 'Gallery created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm|max:51200',
            'external_url' => 'nullable|url|max:2000',
            'is_active' => 'nullable|in:0,1',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_active' => isset($validated['is_active']) ? (bool) $validated['is_active'] : $gallery->is_active,
            'category_id' => $validated['category_id'] ?? null,
        ];

        // update slug lek di update title e
        if ($gallery->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']) . '-' . time();
        }

        // handle external_url or image upload
        if (!empty($validated['external_url'])) {
            // jika sebelumnya file tersimpan di storage, hapus file lama
            if ($gallery->image && !\Str::startsWith($gallery->image, ['http://', 'https://']) && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = $validated['external_url'];
        } elseif ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('galleries', 'public');

            // delete foto lama ketika ada foto baru jika file berada di storage
            if ($gallery->image && !\Str::startsWith($gallery->image, ['http://', 'https://']) && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }

            $data['image'] = $newPath;
        }

        $gallery->update($data);

        return redirect()->route('adminlanding.gallery.index')->with('success', 'Gallery updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Gallery::findOrFail($id);

        // memastikan foto juga ikut terhapus dari folder public jika file local
        if ($gallery->image && !\Str::startsWith($gallery->image, ['http://', 'https://']) && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }

        // soft delete the record
        $gallery->delete();

        return redirect()->route('adminlanding.gallery.index')->with('success', 'Gallery deleted.');
    }
}
