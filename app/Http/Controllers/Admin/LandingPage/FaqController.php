<?php

namespace App\Http\Controllers\Admin\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return a collection named $faqs to the view
        $faqs = faq::orderBy('id', 'asc')
            ->get();
        return view('menu.adminlanding.faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menu.adminlanding.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:512',
            'answer' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Ensure answer is never null (fallback to submitted input or empty string).
        $data['answer'] = $request->input('answer', $data['answer'] ?? '');
        $data['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        faq::create($data);

        return redirect()->route('adminlanding.faq.index')->with('success', 'FAQ created.');
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
        $faq = faq::findOrFail($id);
        return view('menu.adminlanding.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $faq = faq::findOrFail($id);

        $data = $request->validate([
            'question' => 'required|string|max:512',
            'answer' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : false;

        $faq->update($data);

        return redirect()->route('adminlanding.faq.index')->with('success', 'FAQ updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $faq = faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('adminlanding.faq.index')->with('success', 'FAQ deleted.');
    }
}
