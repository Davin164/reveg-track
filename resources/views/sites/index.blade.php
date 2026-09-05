@extends('layouts.app')

@section('title', 'Manajemen Blok Lahan Reklamasi')
@section('subtitle', 'Daftar Lokasi & Area Reklamasi PT Bukit Asam & PAMA')

@section('content')
<div class="space-y-6">

    <!-- Header Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-white">Blok Lahan Tambang (Sites)</h3>
            <p class="text-xs text-slate-400">Total {{ $sites->total() }} lokasi terdaftar dalam pemantauan</p>
        </div>
        @if(auth()->user()->isManager())
            <a href="{{ route('sites.create') }}" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center cursor-pointer">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Blok Lahan Baru
            </a>
        @endif
    </div>

    <!-- Sites Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($sites as $site)
            <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between hover:border-slate-700 transition">
                <div>
                    <!-- Status Badge -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-slate-400">UUID: {{ substr($site->id, 0, 8) }}...</span>
                        @if($site->status === 'verified')
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Verified KLHK</span>
                        @elseif($site->status === 'completed')
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-blue-500/20 text-blue-400 border border-blue-500/30">Completed</span>
                        @elseif($site->status === 'in_progress')
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-500/20 text-amber-400 border border-amber-500/30">In Progress</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-800 text-slate-400">Pending</span>
                        @endif
                    </div>

                    <h4 class="text-lg font-bold text-white tracking-tight">{{ $site->name }}</h4>
                    <p class="text-xs text-slate-400 mt-1 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                        {{ $site->location }}
                    </p>

                    <p class="text-xs text-slate-300 mt-3 line-clamp-2 leading-relaxed">{{ $site->description }}</p>

                    <!-- Stats Row -->
                    <div class="grid grid-cols-2 gap-3 my-4 p-3 rounded-2xl bg-slate-950/60 border border-slate-800 text-center">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Luas Area</span>
                            <span class="text-sm font-bold text-white">{{ $site->area_hectares }} Ha</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Rata Survival</span>
                            <span class="text-sm font-bold text-emerald-400">{{ $site->averageSurvivalRate() }}%</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between">
                    <div class="text-xs text-slate-400">
                        Manager: <span class="text-white font-medium">{{ $site->manager->name ?? 'Belum Ditunjuk' }}</span>
                    </div>
                    <a href="{{ route('sites.show', $site->id) }}" class="px-3 py-1.5 text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-emerald-400 rounded-xl transition">
                        Detail &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $sites->links() }}
    </div>

</div>
@endsection
