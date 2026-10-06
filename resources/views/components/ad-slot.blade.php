@props(['ad', 'placement' => 'leaderboard'])

@if($ad && $ad->is_active)
    <div class="my-6 text-center">
        <span class="block text-[10px] uppercase tracking-widest dark:text-gray-500 text-slate-400 mb-1">Sponsor / Iklan</span>
        <a href="{{ $ad->redirect_url }}" target="_blank" rel="noopener noreferrer" class="inline-block overflow-hidden rounded-lg border dark:border-gray-800 border-gray-200 hover:border-amber-500/50 transition-all shadow-md">
            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="max-w-full h-auto mx-auto object-cover">
        </a>
    </div>
@else
    <div class="my-6 p-4 border border-dashed dark:border-gray-800 border-gray-300 rounded-lg text-center dark:bg-gray-900/30 bg-slate-50">
        <span class="block text-xs font-semibold dark:text-gray-400 text-slate-500">Slot Iklan ({{ ucfirst($placement) }}) Tersedia</span>
        <a href="{{ route('pasang-iklan') }}" class="text-xs text-amber-500 hover:underline">Hubungi Tim Marketing B.Today →</a>
    </div>
@endif
