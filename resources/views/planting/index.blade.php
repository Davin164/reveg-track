@extends('layouts.app')

@section('title', 'Catatan Kegiatan Penanaman Bibit')
@section('subtitle', 'Log Rekam Tanam Bibit Pohon di Petak Reklamasi Lahan')

@section('content')
<div class="space-y-6">

    <!-- Action Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Log Kegiatan Tanam (Planting Records)</h3>
            <p class="text-xs text-slate-400">Total {{ $records->total() }} catatan penanaman bibit</p>
        </div>
        <button onclick="document.getElementById('addPlantingModal').classList.remove('hidden')" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center cursor-pointer">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Catat Tanam Baru
        </button>
    </div>

    <!-- Records Table -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950/80 uppercase tracking-wider text-slate-400 border-b border-slate-800 font-bold">
                    <tr>
                        <th class="px-6 py-4">Tgl Tanam</th>
                        <th class="px-6 py-4">Blok & Petak</th>
                        <th class="px-6 py-4">Spesies Pohon</th>
                        <th class="px-6 py-4">Jumlah Bibit</th>
                        <th class="px-6 py-4">Petugas</th>
                        <th class="px-6 py-4">Aksi Monitoring</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($records as $record)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4 font-semibold text-white">{{ $record->planted_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-emerald-400">{{ $record->plot->plot_code ?? '-' }}</span>
                                <span class="text-slate-400 block text-[11px]">{{ $record->plot->site->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-white">{{ $record->species->name ?? '-' }}</span>
                                <span class="text-slate-400 italic block text-[11px]">{{ $record->species->latin_name ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg bg-blue-500/20 text-blue-400 border border-blue-500/30 font-bold">
                                    {{ number_format($record->seedling_count) }} pohon
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-300">{{ $record->recorder->name ?? 'Staff Lapangan' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('monitoring.index', ['record_id' => $record->id]) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-emerald-400 font-semibold rounded-lg transition inline-block">
                                    + Isi Monitoring
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada catatan kegiatan tanam.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-800">
            {{ $records->links() }}
        </div>
    </div>

</div>

<!-- Modal Catat Tanam Baru -->
<div id="addPlantingModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-white mb-4">Catat Tanam Bibit Baru</h3>
        <form action="{{ route('planting.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Pilih Petak Lahan (Plot)</label>
                <select name="plot_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
                    <option value="">-- Pilih Petak Lahan --</option>
                    @foreach($plots as $plot)
                        <option value="{{ $plot->id }}">{{ $plot->plot_code }} ({{ $plot->site->name ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Jenis Pohon (Species)</label>
                <select name="species_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
                    <option value="">-- Pilih Pohon --</option>
                    @foreach($speciesList as $sp)
                        <option value="{{ $sp->id }}">{{ $sp->name }} ({{ $sp->latin_name }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Jumlah Bibit Ditanam</label>
                <input type="number" name="seedling_count" required placeholder="300" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Tanggal Tanam</label>
                <input type="date" name="planted_at" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Catatan Penanaman</label>
                <textarea name="notes" rows="2" placeholder="Dosis pupuk, pupuk kandang..." class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4">
                <button type="button" onclick="document.getElementById('addPlantingModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-500 text-slate-950 font-bold text-xs rounded-xl">Simpan Tanam</button>
            </div>
        </form>
    </div>
</div>
@endsection
