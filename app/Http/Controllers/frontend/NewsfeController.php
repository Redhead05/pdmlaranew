<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
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

        $news = $query->paginate(12)->withQueryString();

        return view('frontend.pages.news', compact('news'));
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
