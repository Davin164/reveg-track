@extends('layouts.app')

@section('title', 'Master Data Jenis Pohon Pionir')
@section('subtitle', 'Daftar Spesies Vegetasi Reklamasi Pascatambang Batu Bara')

@section('content')
<div class="space-y-6">

    <!-- Header Action -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Jenis Pohon (Tree Species)</h3>
            <p class="text-xs text-slate-400">Total {{ $speciesList->count() }} spesies pohon lokal dan pionir lahan tambang</p>
        </div>
        <button onclick="document.getElementById('addSpeciesModal').classList.remove('hidden')" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center cursor-pointer">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Spesies Pohon
        </button>
    </div>

    <!-- Species Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($speciesList as $species)
            <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between hover:border-slate-700 transition">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 mb-4 text-xl">
                        🌱
                    </div>
                    <h4 class="text-lg font-bold text-white tracking-tight">{{ $species->name }}</h4>
                    <p class="text-xs text-emerald-400 italic font-medium mt-0.5">{{ $species->latin_name ?? '-' }}</p>

                    <p class="text-xs text-slate-300 mt-3 leading-relaxed">{{ $species->description }}</p>

                    <div class="mt-4 p-3 rounded-2xl bg-slate-950/60 border border-slate-800/80 text-xs">
                        <span class="text-[10px] uppercase font-bold text-slate-500 block mb-1">Kondisi Tumbuh Ideal:</span>
                        <span class="text-slate-300">{{ $species->ideal_condition ?? 'Toleran kondisi lahan tambang' }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                    <span>Penggunaan Tanam: <strong class="text-white font-bold">{{ $species->planting_records_count }} Kali</strong></span>
                </div>
            </div>
        @endforeach
    </div>

</div>

<!-- Modal Tambah Spesies -->
<div id="addSpeciesModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-white mb-4">Tambah Spesies Pohon Pionir</h3>
        <form action="{{ route('species.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Nama Pohon</label>
                <input type="text" name="name" required placeholder="Contoh: Akasia Mangium" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Nama Latin</label>
                <input type="text" name="latin_name" placeholder="Acacia mangium" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Deskripsi Spesies</label>
                <textarea name="description" rows="3" placeholder="Fungsi pionir, daya tahan tanah masam..." class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white"></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Kondisi Tumbuh Ideal</label>
                <input type="text" name="ideal_condition" placeholder="pH 4.5-6.5, curah hujan tinggi" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4">
                <button type="button" onclick="document.getElementById('addSpeciesModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-500 text-slate-950 font-bold text-xs rounded-xl">Simpan Spesies</button>
            </div>
        </form>
    </div>
</div>
@endsection
