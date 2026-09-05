@extends('layouts.app')

@section('title', 'Laporan Kepatuhan Audit KLHK')
@section('subtitle', 'Auto-Generate Narasi Evaluasi Reklamasi Menggunakan Gemini AI')

@section('content')
<div class="space-y-6">

    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-white">Laporan Audit Reklamasi KLHK</h3>
            <p class="text-xs text-slate-400">Total {{ $reports->total() }} laporan resmi terevaluasi</p>
        </div>

        <!-- Trigger Generate AI Report Modal -->
        <button onclick="document.getElementById('generateReportModal').classList.remove('hidden')" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center cursor-pointer">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Generate Laporan AI Baru
        </button>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($reports as $report)
            <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between hover:border-slate-700 transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-emerald-400 font-mono">{{ $report->period }}</span>
                        @if($report->status === 'submitted')
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Submitted KLHK</span>
                        @elseif($report->status === 'final')
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-blue-500/20 text-blue-400 border border-blue-500/30">Final Approved</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-500/20 text-amber-400 border border-amber-500/30">Draft</span>
                        @endif
                    </div>

                    <h4 class="text-lg font-bold text-white tracking-tight">{{ $report->title }}</h4>
                    <p class="text-xs text-slate-400 mt-1">Blok Lahan: <strong class="text-slate-200">{{ $report->site->name ?? '-' }}</strong></p>

                    <!-- AI Narrative Snippet -->
                    <div class="mt-4 p-4 rounded-2xl bg-slate-950/60 border border-slate-800 text-xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 block mb-1">🤖 Narasi Hasil Gemini AI:</span>
                        <p class="text-slate-300 leading-relaxed line-clamp-3 whitespace-pre-line">{{ $report->ai_narrative }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-400">Dibuat oleh: <strong class="text-white">{{ $report->generator->name ?? 'Sistem AI' }}</strong></span>
                    <a href="{{ route('reports.show', $report->id) }}" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-emerald-400 font-semibold rounded-xl transition">
                        Baca Laporan Lengkap &rarr;
                    </a>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400 text-center py-10 col-span-2">Belum ada laporan kepatuhan dibuat.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $reports->links() }}
    </div>

</div>

<!-- Modal Generate AI Report -->
<div id="generateReportModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-white mb-2">Generate Laporan AI Audit KLHK</h3>
        <p class="text-xs text-slate-400 mb-4">Gemini AI akan membaca seluruh data survival rate, petak lahan, dan jumlah pohon untuk menyusun narasi evaluasi resmi.</p>

        <form id="generateReportForm" action="" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Pilih Blok Lahan Tambang</label>
                <select id="selectReportSite" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
                    <option value="">-- Pilih Blok Lahan --</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->name }} ({{ $site->area_hectares }} Ha)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Periode Evaluasi</label>
                <input type="text" name="period" value="{{ date('F Y') }}" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4">
                <button type="button" onclick="document.getElementById('generateReportModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-400">Batal</button>
                <button type="button" onclick="submitGenerateForm()" class="px-4 py-2 bg-emerald-500 text-slate-950 font-bold text-xs rounded-xl cursor-pointer">
                    ⚡ Generate via Gemini AI
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function submitGenerateForm() {
        const siteId = document.getElementById('selectReportSite').value;
        if (!siteId) {
            alert('Pilih Blok Lahan terlebih dahulu!');
            return;
        }
        const form = document.getElementById('generateReportForm');
        form.action = '/sites/' + siteId + '/reports/generate';
        form.submit();
    }
</script>
@endpush
@endsection
