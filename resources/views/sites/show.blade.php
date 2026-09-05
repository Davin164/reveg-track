@extends('layouts.app')

@section('title', 'Detail Blok Lahan — ' . $site->name)
@section('subtitle', $site->location)

@section('content')
<div class="space-y-8">

    <!-- Header Banner Card -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <span class="px-3 py-1 text-xs font-bold rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 uppercase">
                    Status: {{ str_replace('_', ' ', $site->status) }}
                </span>
                <span class="text-xs text-slate-400 font-mono">ID: {{ $site->id }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ $site->name }}</h1>
            <p class="text-sm text-slate-400 mt-1">{{ $site->description }}</p>
        </div>

        <!-- Quick AI Action -->
        <form action="{{ route('reports.generate', $site->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-5 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Generate Laporan AI Audit KLHK
            </button>
        </form>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
            <span class="text-xs uppercase tracking-wider text-slate-400 block">Luas Area</span>
            <span class="text-2xl font-extrabold text-white mt-1 block">{{ $site->area_hectares }} Ha</span>
        </div>
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
            <span class="text-xs uppercase tracking-wider text-slate-400 block">Total Petak (Plots)</span>
            <span class="text-2xl font-extrabold text-white mt-1 block">{{ $site->plots->count() }} Petak</span>
        </div>
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
            <span class="text-xs uppercase tracking-wider text-slate-400 block">Bibit Ditanam</span>
            <span class="text-2xl font-extrabold text-blue-400 mt-1 block">{{ number_format($site->totalSeedlingsPlanted()) }} Pohon</span>
        </div>
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 text-center">
            <span class="text-xs uppercase tracking-wider text-slate-400 block">Rata Survival Rate</span>
            <span class="text-2xl font-extrabold text-emerald-400 mt-1 block">{{ $site->averageSurvivalRate() }}%</span>
        </div>
    </div>

    <!-- Plots Section -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-white">Daftar Petak Lahan (Plots)</h3>
                <p class="text-xs text-slate-400">Sub-sub petak area penanaman pohon pada {{ $site->name }}</p>
            </div>

            <!-- Add Plot Form Modal trigger -->
            <button onclick="document.getElementById('addPlotModal').classList.remove('hidden')" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-emerald-400 font-semibold text-xs rounded-xl transition cursor-pointer">
                + Tambah Petak Baru
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($site->plots as $plot)
                <div class="p-5 rounded-2xl bg-slate-950/60 border border-slate-800">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-white">{{ $plot->plot_code }}</span>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-slate-800 text-slate-300 uppercase">{{ $plot->status }}</span>
                    </div>
                    <p class="text-xs text-slate-400">Luas: {{ number_format($plot->area_m2) }} m²</p>

                    <div class="mt-4 pt-3 border-t border-slate-800/80 text-xs space-y-1">
                        <p class="text-slate-300 font-medium">Catatan Tanam Terakhir:</p>
                        @php $latestRecord = $plot->plantingRecords->last(); @endphp
                        @if($latestRecord)
                            <p class="text-slate-400">• {{ $latestRecord->species->name ?? '-' }} ({{ number_format($latestRecord->seedling_count) }} bibit)</p>
                            <p class="text-slate-500 text-[11px]">Tanam: {{ $latestRecord->planted_at->format('d M Y') }}</p>
                        @else
                            <p class="text-slate-500 italic">Belum ada kegiatan tanam.</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400 text-center py-6 col-span-3">Belum ada petak lahan ditambahkan pada blok ini.</p>
            @endforelse
        </div>
    </div>

</div>

<!-- Modal Tambah Plot -->
<div id="addPlotModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-white mb-4">Tambah Petak Lahan (Plot)</h3>
        <form action="{{ route('plots.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="site_id" value="{{ $site->id }}">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Kode Petak</label>
                <input type="text" name="plot_code" required placeholder="PLT-A1" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Luas Petak (m²)</label>
                <input type="number" name="area_m2" required placeholder="2500" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
                    <option value="empty">Empty (Kosong)</option>
                    <option value="planted" selected>Planted (Sudah Ditanam)</option>
                    <option value="monitoring">Monitoring</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4">
                <button type="button" onclick="document.getElementById('addPlotModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-500 text-slate-950 font-bold text-xs rounded-xl">Simpan Petak</button>
            </div>
        </form>
    </div>
</div>
@endsection
