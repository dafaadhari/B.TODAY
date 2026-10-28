<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    @include('partials.admin-head', ['title' => 'Login Redaksi CMS - B.Today'])
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block text-3xl font-black tracking-tight text-white mb-2">
                B.<span class="text-amber-500">TODAY</span>
            </a>
            <span class="block text-xs font-extrabold uppercase tracking-widest text-amber-400">CMS Panel Redaksi</span>
        </div>

        @if($errors->any())
            <div role="alert" class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-xs flex gap-2">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 shrink-0" aria-hidden="true" />
                <div class="space-y-1">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Email Redaksi</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-gray-100 outline-none transition-colors" placeholder="dapa@btoday.com">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Password</label>
                <input type="password" name="password" required class="w-full bg-gray-950 border border-gray-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-gray-100 outline-none transition-colors" placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-xs text-gray-400">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-gray-950 border-gray-800 text-amber-500 focus:ring-0">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-xl">
                Masuk ke Dashboard Redaksi
            </button>
        </form>

        <div class="mt-8 text-center text-xs text-gray-500 pt-6 border-t border-gray-800">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 hover:text-amber-400">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
                Kembali ke Portal Publik
            </a>
        </div>
    </div>
</body>
</html>
