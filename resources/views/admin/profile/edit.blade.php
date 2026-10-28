<x-admin-layout title="Profil Saya - B.Today CMS">
    @php
        $joinedAt = $user->created_at?->copy()->locale('id')->timezone('Asia/Jakarta')->translatedFormat('F Y');
        $card = 'bg-gray-900 border border-gray-800 rounded-2xl shadow-xl';
        $cardHeader = 'border-b border-gray-800 p-5 sm:p-6';
        $fieldLabel = 'mb-1.5 block text-xs font-bold text-gray-300';
        $fieldError = 'mt-1.5 text-[11px] font-semibold text-rose-400';
        $fieldInput = 'w-full rounded-xl border bg-gray-950 px-4 py-2.5 text-base text-gray-100 outline-none transition-colors focus:border-amber-500 sm:text-sm';
        $primaryButton = 'inline-flex items-center justify-center gap-1.5 rounded-xl bg-amber-500 px-6 py-3 text-xs font-black uppercase tracking-wider text-gray-950 shadow-lg transition-colors hover:bg-amber-600';
    @endphp

    <div class="mb-6">
        <h1 class="text-2xl font-black text-white">Profil Saya</h1>
        <p class="text-xs text-gray-400">Kelola nama, email, dan password akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Ringkasan Akun -->
        <aside class="{{ $card }} h-fit p-5 sm:p-6 lg:sticky lg:top-24" aria-label="Ringkasan akun">
            <div class="flex items-center gap-4 lg:flex-col lg:text-center">
                <span class="flex w-16 h-16 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-xl font-black text-black lg:w-20 lg:h-20 lg:text-2xl" aria-hidden="true">{{ $user->initials }}</span>
                <div class="min-w-0">
                    <p class="text-base font-bold text-white lg:text-lg">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400 [overflow-wrap:anywhere]">{{ $user->email }}</p>
                    <span class="mt-2 inline-flex items-center gap-1.5 rounded-full border border-amber-500/30 bg-amber-500/10 px-3 py-1 text-[11px] font-bold text-amber-400">
                        <x-heroicon-o-shield-check class="w-4 h-4" aria-hidden="true" />
                        {{ Str::headline($user->role ?? 'super_admin') }}
                    </span>
                </div>
            </div>

            <dl class="mt-5 space-y-3 border-t border-gray-800 pt-5 text-xs">
                @if ($joinedAt)
                    <div class="flex items-center justify-between gap-3">
                        <dt class="flex items-center gap-2 text-gray-400">
                            <x-heroicon-o-calendar class="w-4 h-4" aria-hidden="true" />
                            Bergabung
                        </dt>
                        <dd class="font-semibold text-gray-200">{{ $joinedAt }}</dd>
                    </div>
                @endif
                <div class="flex items-center justify-between gap-3">
                    <dt class="flex items-center gap-2 text-gray-400">
                        <x-heroicon-o-shield-check class="w-4 h-4" aria-hidden="true" />
                        Peran
                    </dt>
                    <dd class="font-semibold text-gray-200">{{ Str::headline($user->role ?? 'super_admin') }}</dd>
                </div>
            </dl>
            <p class="mt-4 text-[11px] leading-relaxed text-gray-500">Peran akun tidak bisa diubah dari halaman ini.</p>
        </aside>

        <div class="space-y-6 lg:col-span-2">
            <!-- Informasi Akun -->
            <section class="{{ $card }}" aria-labelledby="profile-info-title">
                <div class="{{ $cardHeader }}">
                    <h2 id="profile-info-title" class="flex items-center gap-2 text-sm font-bold text-white">
                        <x-heroicon-o-user-circle class="w-5 h-5 text-amber-400" aria-hidden="true" />
                        Informasi Akun
                    </h2>
                    <p class="mt-1 text-xs text-gray-400">Nama tampil di header CMS dan byline artikel yang Anda tulis.</p>
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5 p-5 sm:p-6"
                    x-data="{ email: @js((string) old('email', $user->email)), originalEmail: @js($user->email) }">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="profile-name" class="{{ $fieldLabel }}">Nama lengkap</label>
                        <input id="profile-name" type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name"
                            @error('name', 'profile') aria-invalid="true" aria-describedby="profile-name-error" @enderror
                            class="{{ $fieldInput }} {{ $errors->profile->has('name') ? 'border-rose-500/60' : 'border-gray-800' }}">
                        @error('name', 'profile')
                            <p id="profile-name-error" class="{{ $fieldError }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="profile-email" class="{{ $fieldLabel }}">Email</label>
                        <input id="profile-email" type="email" name="email" x-model="email" value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="email"
                            @error('email', 'profile') aria-invalid="true" aria-describedby="profile-email-error" @enderror
                            class="{{ $fieldInput }} {{ $errors->profile->has('email') ? 'border-rose-500/60' : 'border-gray-800' }}">
                        <p class="mt-1.5 text-[11px] text-gray-500">Dipakai untuk masuk ke CMS.</p>
                        @error('email', 'profile')
                            <p id="profile-email-error" class="{{ $fieldError }}">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hanya muncul saat email diubah: mengganti email login wajib dengan password saat ini. --}}
                    <div x-show="email.trim().toLowerCase() !== originalEmail" x-cloak
                        class="rounded-xl border border-amber-500/30 bg-amber-500/5 p-4">
                        @include('admin.profile.partials.password-field', [
                            'id' => 'profile-current-password',
                            'name' => 'current_password',
                            'label' => 'Password saat ini',
                            'autocomplete' => 'current-password',
                            'bag' => 'profile',
                            'required' => false,
                            'hint' => 'Diperlukan untuk mengganti email karena email dipakai untuk masuk.',
                        ])
                    </div>

                    <div class="flex justify-end border-t border-gray-800 pt-5">
                        <button type="submit" class="{{ $primaryButton }} w-full sm:w-auto">Simpan Perubahan</button>
                    </div>
                </form>
            </section>

            <!-- Ganti Password -->
            <section class="{{ $card }}" aria-labelledby="profile-password-title">
                <div class="{{ $cardHeader }}">
                    <h2 id="profile-password-title" class="flex items-center gap-2 text-sm font-bold text-white">
                        <x-heroicon-o-key class="w-5 h-5 text-amber-400" aria-hidden="true" />
                        Ganti Password
                    </h2>
                    <p class="mt-1 text-xs text-gray-400">Setelah diganti, perangkat lain yang masih login dengan akun ini akan dikeluarkan.</p>
                </div>

                <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-5 p-5 sm:p-6">
                    @csrf
                    @method('PUT')

                    @include('admin.profile.partials.password-field', [
                        'id' => 'password-current',
                        'name' => 'current_password',
                        'label' => 'Password saat ini',
                        'autocomplete' => 'current-password',
                        'bag' => 'password',
                    ])

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        @include('admin.profile.partials.password-field', [
                            'id' => 'password-new',
                            'name' => 'password',
                            'label' => 'Password baru',
                            'autocomplete' => 'new-password',
                            'bag' => 'password',
                            'hint' => 'Minimal 8 karakter dan berbeda dari password saat ini.',
                        ])
                        @include('admin.profile.partials.password-field', [
                            'id' => 'password-confirmation',
                            'name' => 'password_confirmation',
                            'label' => 'Ulangi password baru',
                            'autocomplete' => 'new-password',
                            'bag' => 'password',
                        ])
                    </div>

                    <div class="flex justify-end border-t border-gray-800 pt-5">
                        <button type="submit" class="{{ $primaryButton }} w-full sm:w-auto">Ganti Password</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-admin-layout>
