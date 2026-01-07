<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::with(['detail', 'category'])->latest()->paginate(10);
        $categories = Category::orderBy('name')->get();
        return view('menu.adminlanding.news.index', compact('news', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|in:0,1',
            'image' => 'nullable|image|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('image')) {
            $thumbnailPath = $request->file('image')->store('news_thumbnails', 'public');
        }

        DB::beginTransaction();
        try {
            $slug = Str::slug($validated['title']);
            $original = $slug;
            $i = 1;
            while (News::where('slug', $slug)->exists()) {
                $slug = $original . '-' . $i++;
            }

            $news = News::create([
                'title' => $validated['title'],
                'slug' => $slug,
                // <-- set the correct column name
                'is_active' => isset($validated['is_active']) ? (bool)$validated['is_active'] : false,
                'category_id' => $validated['category_id'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $news->detail()->create([
                'description' => $validated['description'] ?? null,
                'thumbnail' => $thumbnailPath,
                'news_id' => $news->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'News created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            return redirect()->back()->withErrors('Failed to create news.')->withInput();
        }
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
        $news = News::with('detail')->findOrFail($id);
        return view('menu.adminlanding.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|in:0,1',
            'image' => 'nullable|image|max:2048',
        ]);

        $news = News::with('detail')->findOrFail($id);

        $newThumbnailPath = null;
        $oldThumbnail = $news->detail->thumbnail ?? null;

        if ($request->hasFile('image')) {
            $newThumbnailPath = $request->file('image')->store('news_thumbnails', 'public');
        }

        DB::beginTransaction();
        try {
            if ($validated['title'] !== $news->title) {
                $slug = Str::slug($validated['title']);
                $original = $slug;
                $i = 1;
                while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                    $slug = $original . '-' . $i++;
                }
                $news->slug = $slug;
            }

            $news->update([
                'title' => $validated['title'],
                // <-- use `is_active` here too
                'is_active' => isset($validated['is_active']) ? (bool) $validated['is_active'] : $news->is_active,
                'category_id' => $validated['category_id'] ?? $news->category_id,
            ]);

            $detailData = [
                'description' => $validated['description'] ?? null,
            ];
            if ($newThumbnailPath !== null) {
                $detailData['thumbnail'] = $newThumbnailPath;
            }

            $news->detail()->updateOrCreate(
                ['news_id' => $news->id],
                $detailData
            );

            DB::commit();

            if ($newThumbnailPath && $oldThumbnail && Storage::disk('public')->exists($oldThumbnail)) {
                Storage::disk('public')->delete($oldThumbnail);
            }

            return redirect()->back()->with('success', 'News updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($newThumbnailPath && Storage::disk('public')->exists($newThumbnailPath)) {
                Storage::disk('public')->delete($newThumbnailPath);
            }
            return redirect()->back()->withErrors('Failed to update news.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $news = News::with('detail')->findOrFail($id);
        $thumbnail = $news->detail->thumbnail ?? null;

        DB::beginTransaction();
        try {
            // delete detail (soft delete) and the news (soft delete)
            if ($news->detail) {
                $news->detail()->delete();
            }
            $news->delete();

            DB::commit();

            // delete file from storage after successful commit
            if ($thumbnail && Storage::disk('public')->exists($thumbnail)) {
                Storage::disk('public')->delete($thumbnail);
            }

            return redirect()->back()->with('success', 'News deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Failed to delete news.');
        }
    }
}
