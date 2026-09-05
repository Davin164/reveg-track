@extends('layouts.app')

@section('title', 'Dashboard Monitoring Reklamasi')
@section('subtitle', 'Ikhtisar Progres Vegetasi & Analisis AI Reklamasi Lahan Tambang')

@section('content')
<div class="space-y-8">

    <!-- Metric Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1 -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Blok Lahan</p>
                    <h3 class="text-3xl font-extrabold text-white mt-1">{{ $sitesCount }}</h3>
                    <p class="text-xs text-emerald-400 font-medium mt-1">{{ $totalHectares }} Ha Total Area</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-xl"></div>
        </div>

        <!-- Card 2 -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Bibit Ditanam</p>
                    <h3 class="text-3xl font-extrabold text-white mt-1">{{ number_format($totalSeedlings) }}</h3>
                    <p class="text-xs text-blue-400 font-medium mt-1">{{ $speciesCount }} Jenis Pohon Pionir</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-emerald-500/20 flex items-center justify-center text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/5 rounded-full blur-xl"></div>
        </div>

        <!-- Card 3 -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rata-rata Survival Rate</p>
                    <h3 class="text-3xl font-extrabold text-emerald-400 mt-1">{{ round($overallSurvivalRate, 1) }}%</h3>
                    <p class="text-xs text-slate-400 font-medium mt-1">Target KLHK &gt; 80%</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl"></div>
        </div>

        <!-- Card 4 -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengaduan Warga</p>
                    <h3 class="text-3xl font-extrabold text-white mt-1">{{ $pendingComplaintsCount }}</h3>
                    <p class="text-xs text-amber-400 font-medium mt-1">Pending Perlu Ditanggapi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/5 rounded-full blur-xl"></div>
        </div>
    </div>

    <!-- Charts Section (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart 1: Survival Rate per Blok -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-white">Tingkat Keberhasilan Tumbuh (Survival Rate)</h3>
                    <p class="text-xs text-slate-400">Persentase pohon hidup per blok lahan tambang</p>
                </div>
                <span class="px-2.5 py-1 text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-lg">Chart.js</span>
            </div>
            <div class="h-64">
                <canvas id="chartSiteSurvival"></canvas>
            </div>
        </div>

        <!-- Chart 2: Monthly Trend -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-white">Tren Pemantauan Bulanan</h3>
                    <p class="text-xs text-slate-400">Progres perkembangan kesehatan vegetasi lahan</p>
                </div>
                <span class="px-2.5 py-1 text-[11px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/30 rounded-lg">Realtime AI Logs</span>
            </div>
            <div class="h-64">
                <canvas id="chartMonthlyTrend"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Vision AI Monitoring Logs & Complaints -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Monitoring Logs (2 Cols) -->
        <div class="lg:col-span-2 bg-slate-900/80 border border-slate-800 rounded-3xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-white">Monitoring Terbaru & Analisis Gemini Vision AI</h3>
                    <p class="text-xs text-slate-400">Log pemantauan kesehatan pohon terkini dari petugas lapangan</p>
                </div>
                <a href="{{ route('monitoring.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300 font-semibold transition">Lihat Semua &rarr;</a>
            </div>

            <div class="space-y-4">
                @forelse($recentLogs as $log)
                    <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            @if($log->photos->first())
                                <img src="{{ asset($log->photos->first()->file_path) }}" alt="Plant Photo" class="w-14 h-14 rounded-xl object-cover border border-slate-700 shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-500 shrink-0">
                                    🌿
                                </div>
                            @endif
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h4 class="text-sm font-bold text-white">{{ $log->plantingRecord->species->name ?? 'Spesies' }}</h4>
                                    <span class="text-xs text-slate-400">({{ $log->plantingRecord->plot->site->name ?? '-' }})</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-0.5">Petak {{ $log->plantingRecord->plot->plot_code ?? '-' }} &bull; Monitored: {{ $log->monitored_at->format('d M Y') }}</p>
                                <p class="text-xs text-slate-300 mt-1 line-clamp-1 italic">"{{ $log->ai_notes }}"</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 shrink-0">
                            <div class="text-right">
                                <div class="text-xs font-bold text-slate-200">{{ $log->survival_rate }}% Survival</div>
                                <div class="text-[11px] text-slate-400">{{ $log->alive_count }} Hidup / {{ $log->dead_count }} Mati</div>
                            </div>
                            @if($log->ai_condition === 'healthy')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">Healthy</span>
                            @elseif($log->ai_condition === 'wilting')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-500/20 text-amber-400 border border-amber-500/40">Wilting</span>
                            @elseif($log->ai_condition === 'dead')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-500/20 text-rose-400 border border-rose-500/40">Dead</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-800 text-slate-300">Unknown</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 text-center py-6">Belum ada data monitoring.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Complaints (1 Col) -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-white">Pengaduan Warga</h3>
                    <p class="text-xs text-slate-400">Suara masyarakat sekitar tambang</p>
                </div>
                <a href="{{ route('complaints.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300 font-semibold transition">Kelola &rarr;</a>
            </div>

            <div class="space-y-4">
                @forelse($recentComplaints as $complaint)
                    <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 truncate">{{ $complaint->site->name ?? '-' }}</span>
                            @if($complaint->status === 'resolved')
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Resolved</span>
                            @else
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">Pending</span>
                            @endif
                        </div>
                        <h5 class="text-xs font-bold text-white line-clamp-1">{{ $complaint->title }}</h5>
                        <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $complaint->description }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 text-center py-6">Belum ada pengaduan warga.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Chart 1: Survival Rate per Site
        const ctxSite = document.getElementById('chartSiteSurvival').getContext('2d');
        new Chart(ctxSite, {
            type: 'bar',
            data: {
                labels: {!! json_encode($siteLabels) !!},
                datasets: [{
                    label: 'Survival Rate (%)',
                    data: {!! json_encode($siteSurvivalData) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                    borderColor: '#10b981',
                    borderWidth: 1.5,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Chart 2: Monthly Trend
        const ctxTrend = document.getElementById('chartMonthlyTrend').getContext('2d');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthLabels) !!},
                datasets: [{
                    label: 'Rata-rata Survival Rate (%)',
                    data: {!! json_encode($monthSurvivalData) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush
