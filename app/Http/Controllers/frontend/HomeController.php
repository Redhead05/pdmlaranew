<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsDetail;

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = NewsDetail::with('news')
            ->whereHas('news', function ($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('created_at','desc')
            ->take(3)
            ->get();
//        dd($latestNews->jsonSerialize());

        return view('frontend.pages.home', compact('latestNews'));
    }
}
