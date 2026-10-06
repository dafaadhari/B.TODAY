<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    public function index()
    {
        $ads = Advertisement::latest()->get();
        return view('admin.ads.index', compact('ads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image_url' => 'required|url',
            'redirect_url' => 'required|url|regex:/^https?:\/\//i',
            'placement' => 'required|in:leaderboard,sidebar,in_article',
            'is_active' => 'boolean',
        ]);

        $ad = Advertisement::create([
            'title' => $request->title,
            'image_url' => $request->image_url,
            'redirect_url' => $request->redirect_url,
            'placement' => $request->placement,
            'is_active' => $request->has('is_active'),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create_advertisement',
            'subject_type' => Advertisement::class,
            'subject_id' => $ad->id,
        ]);

        return redirect()->back()->with('success', 'Slot iklan berhasil ditambahkan!');
    }

    public function toggleActive(Advertisement $ad)
    {
        $ad->is_active = ! $ad->is_active;
        $ad->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'toggle_advertisement_status',
            'subject_type' => Advertisement::class,
            'subject_id' => $ad->id,
        ]);

        return redirect()->back()->with('success', 'Status penayangan iklan berhasil diperbarui!');
    }

    public function destroy(Advertisement $ad)
    {
        $ad->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_advertisement',
            'subject_type' => Advertisement::class,
            'subject_id' => $ad->id,
        ]);

        return redirect()->back()->with('success', 'Iklan berhasil dihapus.');
    }
}
