<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuestSubmissionRequest;
use App\Models\Category;
use App\Models\GuestSubmission;

class GuestSubmissionController extends Controller
{
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('kirim-tulisan', compact('categories'));
    }

    public function store(GuestSubmissionRequest $request)
    {
        GuestSubmission::create([
            'name' => $request->name,
            'instansi' => $request->instansi,
            'email' => $request->email,
            'title' => $request->title,
            'category_id' => $request->category_id,
            'content' => $request->content,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Tulisan Anda berhasil dikirim dan saat ini dalam antrean peninjauan tim Redaksi B.Today. Terima kasih atas partisipasi Anda!');
    }
}
