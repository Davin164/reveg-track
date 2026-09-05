@extends('layouts.app')

@section('title', 'Pemantauan & Gemini Vision AI')
@section('subtitle', 'Log Monitoring Lapangan & Analisis Otomatis Kesehatan Tanaman dari Foto')

@section('content')
<div class="space-y-6">

    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-white">Monitoring & Vision AI</h3>
            <p class="text-xs text-slate-400">Upload foto tanaman untuk dianalisis Gemini AI secara otomatis</p>
        </div>
        <button onclick="document.getElementById('addMonitoringModal').classList.remove('hidden')" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center cursor-pointer">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l0.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l0.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            + Input Monitoring & Upload Foto AI
        </button>
    </div>

    <!-- Logs Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($logs as $log)
            <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between hover:border-slate-700 transition">
                <div>
                    <!-- Photo Header -->
                    <div class="relative mb-4 rounded-2xl overflow-hidden bg-slate-950 aspect-video border border-slate-800">
                        @if($log->photos->first())
                            <img src="{{ asset($log->photos->first()->file_path) }}" alt="Plant Photo" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-600">
                                <span class="text-3xl mb-1">🌿</span>
                                <span class="text-xs">Foto Belum Diunggah</span>
                            </div>
                        @endif

                        <!-- Vision AI Condition Badge Overlay -->
                        <div class="absolute top-3 right-3">
                            @if($log->ai_condition === 'healthy')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/90 text-slate-950 backdrop-blur-md shadow-lg">
                                    Subur ({{ $log->ai_health_score }}%)
                                </span>
                            @elseif($log->ai_condition === 'wilting')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-500/90 text-slate-950 backdrop-blur-md shadow-lg">
                                    Layu ({{ $log->ai_health_score }}%)
                                </span>
                            @elseif($log->ai_condition === 'dead')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-500/90 text-white backdrop-blur-md shadow-lg">
                                    Mati ({{ $log->ai_health_score }}%)
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-950/90 text-amber-300 border border-amber-500/40 backdrop-blur-md">
                                    Potensi Tidak Hidup ({{ $log->ai_health_score ?? 0 }}%)
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-emerald-400">{{ $log->plantingRecord->species->name ?? '-' }}</span>
                        <span class="text-[11px] text-slate-400 font-mono">{{ $log->monitored_at->format('d M Y') }}</span>
                    </div>

                    <h4 class="text-sm font-bold text-white">Petak {{ $log->plantingRecord->plot->plot_code ?? '-' }}</h4>
                    <p class="text-xs text-slate-400">{{ $log->plantingRecord->plot->site->name ?? '-' }}</p>

                    <!-- Metrics -->
                    <div class="grid grid-cols-3 gap-2 my-3 p-2.5 rounded-xl bg-slate-950/60 border border-slate-800 text-center text-xs">
                        <div>
                            <span class="text-[10px] text-slate-500 block">Hidup</span>
                            <span class="font-bold text-emerald-400">{{ $log->alive_count }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 block">Mati</span>
                            <span class="font-bold text-rose-400">{{ $log->dead_count }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-500 block">Survival</span>
                            <span class="font-bold text-white">{{ $log->survival_rate }}%</span>
                        </div>
                    </div>

                    <!-- Gemini AI Diagnostics -->
                    <div class="p-3 rounded-2xl bg-emerald-950/20 border border-emerald-500/20 text-xs">
                        <div class="flex items-center space-x-1 text-emerald-400 font-bold mb-1 text-[11px]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Gemini AI Vision Notes:</span>
                        </div>
                        <p class="text-slate-300 leading-relaxed italic">{{ $log->ai_notes ?? 'Belum ada analisa AI.' }}</p>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                    <span>Staff: <strong class="text-white">{{ $log->logger->name ?? 'Petugas Lapangan' }}</strong></span>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400 text-center py-10 col-span-3">Belum ada data pemantauan.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $logs->links() }}
    </div>

</div>

<!-- Modal Input Monitoring & Photo Upload -->
<div id="addMonitoringModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-white">Input Monitoring & Vision AI</h3>
            <button type="button" onclick="document.getElementById('addMonitoringModal').classList.add('hidden')" class="text-slate-400 hover:text-white">&times;</button>
        </div>

        <form action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Pilih Catatan Tanam (Plot & Pohon)</label>
                <select name="planting_record_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
                    <option value="">-- Pilih Catatan Tanam --</option>
                    @foreach($plantingRecords as $rec)
                        <option value="{{ $rec->id }}" {{ (isset($record_id) && $record_id == $rec->id) ? 'selected' : '' }}>
                            Petak {{ $rec->plot->plot_code ?? '-' }} — {{ $rec->species->name ?? '-' }} ({{ $rec->plot->site->name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Jumlah Pohon Hidup</label>
                    <input type="number" name="alive_count" required placeholder="280" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Jumlah Pohon Mati</label>
                    <input type="number" name="dead_count" required placeholder="20" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Tanggal Pemantauan</label>
                <input type="date" name="monitored_at" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
            </div>

            <!-- Upload Photo for Gemini Vision AI -->
            <div class="p-4 rounded-2xl bg-emerald-950/20 border border-emerald-500/30">
                <label class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-1">📸 Upload Foto Tanaman (Trigger Gemini Vision AI)</label>
                <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-500 file:text-slate-950 hover:file:bg-emerald-400 cursor-pointer">
                <p class="text-[11px] text-slate-400 mt-2">Foto akan langsung dikirim ke Gemini AI Studio untuk dianalisa kondisi (Healthy/Wilting/Dead) dan skor kesehatannya.</p>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('addMonitoringModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-400">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition cursor-pointer">
                    Simpan & Analisis AI
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
