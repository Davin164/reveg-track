@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 space-y-8">
    <div class="space-y-2">
        <a href="{{ route('public.portal') }}" class="text-sm font-bold text-slate-400 hover:text-emerald-400 transition">&larr; Kembali ke Portal</a>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight pt-2">Galeri Foto Vegetasi Lahan</h1>
        <p class="text-slate-400 text-sm">Arsip lengkap dokumentasi foto progres reklamasi dari seluruh blok lahan tambang.</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
        <form action="{{ route('public.gallery') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/3">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Filter Lokasi Lahan</label>
                <select name="site_id" class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-emerald-500 text-sm">
                    <option value="">-- Semua Lokasi --</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>
                            {{ $site->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-1/3">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Filter Tanggal Foto</label>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-emerald-500 text-sm" style="color-scheme: dark;">
            </div>

            <div class="w-full md:w-auto flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm rounded-xl transition">
                    Terapkan Filter
                </button>
                @if(request('site_id') || request('date'))
                    <a href="{{ route('public.gallery') }}" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-sm rounded-xl transition border border-slate-700">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($photos as $photo)
            <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 aspect-square">
                <img src="{{ asset($photo->file_path) }}" alt="Reclamation Progress" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent p-4 flex flex-col justify-end opacity-90 group-hover:opacity-100 transition">
                    <span class="text-xs font-bold text-white">{{ $photo->monitoringLog->plantingRecord->species->name ?? 'Spesies Pohon' }}</span>
                    <span class="text-[10px] text-emerald-400 font-medium">{{ $photo->monitoringLog->plantingRecord->plot->site->name ?? '-' }}</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Geotag: {{ $photo->geotag_lat }}, {{ $photo->geotag_lng }}</span>
                    <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($photo->taken_at)->translatedFormat('d M Y') }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500">
                Tidak ada foto yang ditemukan untuk filter tersebut.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $photos->links() }}
    </div>
</div>
@endsection
