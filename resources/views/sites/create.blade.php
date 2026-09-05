@extends('layouts.app')

@section('title', 'Tambah Blok Lahan Baru')
@section('subtitle', 'Pendaftaran Kawasan Reklamasi Tambang PT BA & PAMA')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-8 shadow-2xl">
        <form action="{{ route('sites.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Blok Lahan Tambang</label>
                    <input type="text" name="name" required placeholder="Contoh: Blok Air Laya Pit 2 West"
                           class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Lokasi Wilayah</label>
                    <input type="text" name="location" required placeholder="Contoh: Tanjung Enim, Muara Enim"
                           class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Luas Area (Hektar)</label>
                    <input type="number" step="0.01" name="area_hectares" required placeholder="100.50"
                           class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Status Progress Reklamasi</label>
                    <select name="status" required class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                        <option value="pending">Pending (Persiapan)</option>
                        <option value="in_progress" selected>In Progress (Aktif Ditanami)</option>
                        <option value="completed">Completed (Penutupan Tajuk)</option>
                        <option value="verified">Verified (Terverifikasi KLHK)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Manager Penanggung Jawab</label>
                    <select name="managed_by" class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                        <option value="">-- Pilih Manager --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->role }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Deskripsi / Catatan Kondisi Lahan</label>
                    <textarea name="description" rows="4" placeholder="Kondisi topsoil, lereng disposal, vegetasi awal..."
                              class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                <a href="{{ route('sites.index') }}" class="px-5 py-3 text-xs font-semibold text-slate-400 hover:text-white transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition cursor-pointer">
                    Simpan Blok Lahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
