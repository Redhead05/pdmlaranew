<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\News;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $news = News::with(['detail', 'category'])
            ->where('is_active', 1)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('frontend.pages.news', compact('news'));
    }
}
