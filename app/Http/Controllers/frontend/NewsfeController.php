<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;

class NewsfeController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['detail', 'category'])
            ->where('is_active', 1)
            ->orderByDesc('created_at');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('detail', function ($qd) use ($search) {
                        $qd->where('description', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by category id if provided
        if ($category = $request->input('category')) {
            $query->where('category_id', $category);
        }

        $news = $query->paginate(12)->withQueryString();

        $categories = Category::withCount(['news' => function ($q) {
            $q->where('is_active', 1);
        }])
            ->orderBy('name')
            ->get();

        return view('frontend.pages.news', compact('news','categories'));
    }
    public function show(string $slug)
    {
        $news = News::with(['detail', 'category'])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        return view('frontend.pages.news-details', compact('news'));
    }
}
