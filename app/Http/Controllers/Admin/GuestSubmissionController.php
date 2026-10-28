<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\AuditLog;
use App\Models\GuestSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GuestSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('q');

        $submissionsQuery = GuestSubmission::with(['category', 'reviewer', 'article']);

        if ($status) {
            $submissionsQuery->where('status', $status);
        }

        if ($search) {
            $submissionsQuery->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }

        $submissions = $submissionsQuery->latest()->paginate($this->adminPerPage($request))->withQueryString();

        $stats = [
            'total' => GuestSubmission::count(),
            'pending' => GuestSubmission::where('status', 'pending')->count(),
            'approved' => GuestSubmission::where('status', 'approved')->count(),
            'rejected' => GuestSubmission::where('status', 'rejected')->count(),
        ];

        return view('admin.submissions.index', compact('submissions', 'stats', 'status', 'search'));
    }

    public function approve(GuestSubmission $submission)
    {
        if ($submission->status === 'approved') {
            return redirect()->back()->with('error', 'Kiriman ini sudah disetujui sebelumnya.');
        }

        $currentUserId = Auth::id();

        // 1. Buat artikel baru dari kiriman publik (author_id = NULL)
        $article = Article::create([
            'author_id' => null,
            'category_id' => $submission->category_id ?? 1,
            'title' => $submission->title,
            'slug' => Str::slug($submission->title).'-'.Str::random(5),
            'content' => $submission->content,
            'guest_author_name' => $submission->name,
            'guest_author_instansi' => $submission->instansi,
            'reviewed_by' => $currentUserId,
            'reviewed_at' => now(),
            'status' => 'published',
            'published_at' => now(),
        ]);

        // 2. Update status submission
        $submission->update([
            'status' => 'approved',
            'reviewed_by' => $currentUserId,
            'article_id' => $article->id,
        ]);

        // 3. Catat audit log
        AuditLog::create([
            'user_id' => $currentUserId,
            'action' => 'approve_guest_submission',
            'subject_type' => GuestSubmission::class,
            'subject_id' => $submission->id,
        ]);

        return redirect()->back()->with('success', 'Kiriman publik berhasil disetujui! Artikel telah dibuat dan ditayangkan dengan byline penulis publik.');
    }

    public function reject(Request $request, GuestSubmission $submission)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $submission->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'reject_guest_submission',
            'subject_type' => GuestSubmission::class,
            'subject_id' => $submission->id,
        ]);

        return redirect()->back()->with('success', 'Kiriman publik berhasil ditolak beserta alasan penolakan.');
    }
}
