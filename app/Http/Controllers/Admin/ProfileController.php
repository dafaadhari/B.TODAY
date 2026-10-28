<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', ['user' => $request->user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->update($request->safe()->only(['name', 'email']));

        $this->recordAudit($user, 'update_profile');

        return redirect()->route('admin.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Setelah password diganti, sesi di perangkat lain ikut keluar (middleware `auth.session`).
     */
    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->update(['password' => $request->validated('password')]);

        Auth::logoutOtherDevices($request->validated('password'));

        $this->recordAudit($user, 'update_password');

        return redirect()->route('admin.profile.edit')->with('success', 'Password berhasil diganti. Perangkat lain yang masih login telah dikeluarkan.');
    }

    private function recordAudit(User $user, string $action): void
    {
        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'subject_type' => User::class,
            'subject_id' => $user->id,
        ]);
    }
}
