@extends('layouts.public')

@section('content')
<div class="space-y-16 py-12">

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 text-center space-y-6">
        <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold text-xs uppercase tracking-wider inline-block">
            Komitmen Lingkungan PT Bukit Asam Tbk & PAMA
        </span>
        <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-tight max-w-4xl mx-auto">
            Transparansi Pemantauan <span class="text-emerald-400">Reklamasi Lahan</span> Pascatambang
        </h1>
        <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
            Platform monitoring berbasis kecerdasan buatan (Gemini AI) untuk mempercepat pemulihan ekosistem hutan dan memenuhi standar evaluasi lingkungan KLHK.
        </p>

        <!-- Call to Actions -->
        <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
            <a href="#sites" class="px-6 py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-sm rounded-2xl shadow-xl shadow-emerald-500/25 transition">
                Jelajahi Blok Reklamasi
            </a>
            <a href="#complaint" class="px-6 py-3.5 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-200 font-bold text-sm rounded-2xl transition">
                Kirim Pengaduan Warga
            </a>
        </div>
    </section>

    <!-- Stats Banner (#stats) -->
    <section id="stats" class="max-w-7xl mx-auto px-6">
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-8 grid grid-cols-2 lg:grid-cols-4 gap-8 shadow-2xl">
            <div class="text-center space-y-1">
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Total Luas Reklamasi</span>
                <h2 class="text-4xl font-extrabold text-white">{{ $totalHectares }} <span class="text-emerald-400 text-2xl">Ha</span></h2>
                <p class="text-xs text-slate-500">Tanjung Enim & Lahat</p>
            </div>
            <div class="text-center space-y-1">
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Bibit Pohon Ditanam</span>
                <h2 class="text-4xl font-extrabold text-white">{{ number_format($totalSeedlings) }}</h2>
                <p class="text-xs text-slate-500">Pohon Pionir & Lokal</p>
            </div>
            <div class="text-center space-y-1">
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Average Survival Rate</span>
                <h2 class="text-4xl font-extrabold text-emerald-400">{{ $avgSurvivalRate }}%</h2>
                <p class="text-xs text-emerald-500/80 font-medium">Lolos Standar Audit KLHK</p>
            </div>
            <div class="text-center space-y-1">
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Blok Lahan Aktif</span>
                <h2 class="text-4xl font-extrabold text-white">{{ $sitesCount }} <span class="text-blue-400 text-2xl">Blok</span></h2>
                <p class="text-xs text-slate-500">Dalam Pemantauan AI</p>
            </div>
        </div>
    </section>

    <!-- Sites Overview (#sites) -->
    <section id="sites" class="max-w-7xl mx-auto px-6 space-y-8">
        <div class="text-center space-y-2">
            <h2 class="text-3xl font-extrabold text-white tracking-tight">Kawasan Reklamasi Tambang Batu Bara</h2>
            <p class="text-slate-400 text-sm">Status pemulihan lahan pascatambang di wilayah Sumatera Selatan</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($sites as $site)
                <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between hover:border-slate-700 transition">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                {{ strtoupper($site->status) }}
                            </span>
                            <span class="text-xs font-bold text-slate-400">{{ $site->area_hectares }} Hektar</span>
                        </div>
                        <h3 class="text-xl font-bold text-white tracking-tight">{{ $site->name }}</h3>
                        <p class="text-xs text-slate-400 mt-1 flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            {{ $site->location }}
                        </p>

                        <p class="text-xs text-slate-300 mt-3 line-clamp-3 leading-relaxed">{{ $site->description }}</p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-slate-400">Survival Rate: <strong class="text-emerald-400">{{ $site->averageSurvivalRate() }}%</strong></span>
                        <a href="{{ route('public.siteDetail', $site->id) }}" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-emerald-400 text-xs font-bold rounded-xl transition">
                            Lihat Rincian &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Photo Progress Gallery -->
    <section class="max-w-7xl mx-auto px-6 space-y-8">
        <div class="text-center space-y-2">
            <h2 class="text-3xl font-extrabold text-white tracking-tight">Galeri Foto Progres Vegetasi</h2>
            <p class="text-slate-400 text-sm">Dokumentasi foto nyata dari lapangan hasil monitoring petugas dan analisis Vision AI</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($recentPhotos as $photo)
                <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 aspect-square">
                    <img src="{{ asset($photo->file_path) }}" alt="Reclamation Progress" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent p-4 flex flex-col justify-end opacity-90 group-hover:opacity-100 transition">
                        <span class="text-xs font-bold text-white">{{ $photo->monitoringLog->plantingRecord->species->name ?? 'Spesies Pohon' }}</span>
                        <span class="text-[10px] text-emerald-400 font-medium">{{ $photo->monitoringLog->plantingRecord->plot->site->name ?? '-' }}</span>
                        <span class="text-[10px] text-slate-400 mt-0.5">Geotag: {{ $photo->geotag_lat }}, {{ $photo->geotag_lng }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center pt-4">
            <a href="{{ route('public.gallery') }}" class="inline-flex items-center justify-center px-6 py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-slate-600 text-white font-bold text-sm rounded-xl transition">
                Lihat Seluruh Galeri & Filter &rarr;
            </a>
        </div>
    </section>

    <!-- Public Complaint Form (#complaint) -->
    <section id="complaint" class="max-w-4xl mx-auto px-6">
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-8 md:p-10 shadow-2xl space-y-6">
            <div class="text-center space-y-2">
                <span class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold text-xs uppercase tracking-wider inline-block">
                    Aspirasi & Keluhan Warga
                </span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-white">Formulir Pengaduan Lingkungan</h2>
                <p class="text-slate-400 text-xs md:text-sm max-w-xl mx-auto">
                    Laporkan keluhan atau masukan Anda terkait area reklamasi lahan tambang sekitar pemukiman. Tim K3L PT Bukit Asam & PAMA akan menindaklanjuti secara resmi.
                </p>
            </div>

            @if(session('success'))
                <div class="p-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-center flex flex-col items-center justify-center space-y-3">
                    <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="font-bold text-lg">{{ session('success') }}</p>
                        <p class="text-sm mt-1">Laporan Anda akan diproses secara berkala oleh tim. Terima kasih.</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('complaints.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Pilih Lokasi Blok Tambang</label>
                    <select name="site_id" required class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white focus:outline-none focus:border-emerald-500">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->name }} ({{ $site->location }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Judul Pengaduan / Keluhan</label>
                    <input type="text" name="title" required placeholder="Contoh: Indikasi Foto Fake / Pohon Layu Dipaksakan / Drainase Tersumbat"
                           class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Rincian Deskripsi Pengaduan</label>
                    <textarea name="description" rows="4" required placeholder="Jelaskan secara rinci situasi lapangan..."
                              class="w-full px-4 py-3 bg-slate-950/80 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500"></textarea>
                </div>

                <button type="submit" class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-sm rounded-xl shadow-xl shadow-emerald-500/20 transition cursor-pointer">
                    Kirimkan Pengaduan Warga
                </button>
            </form>
        </div>
    </section>

    <!-- Recent Complaints List -->
    <section class="max-w-4xl mx-auto px-6 mt-16 space-y-8">
        <div class="text-center space-y-2">
            <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Daftar Laporan Warga Terbaru</h2>
            <p class="text-slate-400 text-sm">Transparansi tindak lanjut pelaporan dari warga sekitar area tambang</p>
        </div>

        <div class="space-y-4">
            @forelse($publicComplaints as $complaint)
                <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs text-slate-400 font-medium">{{ $complaint->created_at->diffForHumans() }} &bull; {{ $complaint->site->name }}</span>
                        @if($complaint->status == 'pending')
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-amber-500/20 text-amber-400 border border-amber-500/30 uppercase tracking-wide">Menunggu Diproses</span>
                        @elseif($complaint->status == 'resolved')
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 uppercase tracking-wide">Selesai Ditindaklanjuti</span>
                        @elseif($complaint->status == 'reviewed')
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-blue-500/20 text-blue-400 border border-blue-500/30 uppercase tracking-wide">Sedang Ditinjau</span>
                        @else
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-red-500/20 text-red-400 border border-red-500/30 uppercase tracking-wide">Ditolak</span>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">{{ $complaint->title }}</h3>
                    <p class="text-sm text-slate-400 leading-relaxed mb-4">{{ $complaint->description }}</p>

                    @if($complaint->response)
                        <div class="bg-slate-950/50 rounded-xl p-4 border border-slate-800 border-l-2 border-l-emerald-500">
                            <span class="block text-xs font-bold text-emerald-400 mb-1">Tanggapan Admin PT BA / PAMA:</span>
                            <p class="text-sm text-slate-300">{{ $complaint->response }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-slate-500">Belum ada aduan warga terbaru.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection
