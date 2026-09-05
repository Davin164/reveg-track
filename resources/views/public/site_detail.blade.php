@extends('layouts.public')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12 space-y-8">

    <a href="{{ route('public.portal') }}#sites" class="text-xs font-semibold text-slate-400 hover:text-emerald-400 flex items-center transition">
        &larr; Kembali ke Portal Publik
    </a>

    <!-- Header Card -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-8 space-y-4">
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 text-xs font-bold rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 uppercase">
                Status: {{ str_replace('_', ' ', $site->status) }}
            </span>
            <span class="text-xs text-slate-400 font-mono">Luas: {{ $site->area_hectares }} Hektar</span>
        </div>

        <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ $site->name }}</h1>
        <p class="text-xs text-slate-400 flex items-center">
            <svg class="w-4 h-4 mr-1 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            </svg>
            {{ $site->location }}
        </p>
        <p class="text-sm text-slate-300 leading-relaxed">{{ $site->description }}</p>
    </div>

    <!-- Plots & Species Table -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-8 space-y-6">
        <h3 class="text-lg font-bold text-white">Rincian Petak (Plots) & Vegetasi</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($site->plots as $plot)
                <div class="p-5 rounded-2xl bg-slate-950/60 border border-slate-800 space-y-2">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-white">Petak {{ $plot->plot_code }}</h4>
                        <span class="text-xs text-slate-400">{{ number_format($plot->area_m2) }} m²</span>
                    </div>

                    @foreach($plot->plantingRecords as $rec)
                        <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800/80 text-xs">
                            <span class="font-bold text-emerald-400 block">{{ $rec->species->name ?? '-' }}</span>
                            <span class="text-slate-400 block mt-0.5">Ditanam: {{ number_format($rec->seedling_count) }} Pohon &bull; Tgl: {{ $rec->planted_at->format('d M Y') }}</span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
