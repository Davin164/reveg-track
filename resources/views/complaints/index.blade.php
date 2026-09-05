@extends('layouts.app')

@section('title', 'Manajemen Keluhan Warga')
@section('subtitle', 'Pengaduan & Aspirasi Masyarakat Terkait Lahan Pascatambang')

@section('content')
<div class="space-y-6">

    <!-- Action Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-white">Daftar Pengaduan Warga</h3>
            <p class="text-xs text-slate-400">Total {{ $complaints->total() }} laporan dari masyarakat</p>
        </div>
    </div>

    <!-- Complaints Grid -->
    <div class="space-y-4">
        @forelse($complaints as $complaint)
            <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 hover:border-slate-700 transition">
                <div class="space-y-2 flex-1">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-bold text-emerald-400">{{ $complaint->site->name ?? 'Lokasi Umum' }}</span>
                        <span class="text-slate-500">&bull;</span>
                        <span class="text-xs text-slate-400">{{ $complaint->created_at->diffForHumans() }}</span>
                        @if($complaint->status === 'resolved')
                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Resolved</span>
                        @elseif($complaint->status === 'reviewed')
                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-lg bg-blue-500/20 text-blue-400 border border-blue-500/30">Reviewed</span>
                        @elseif($complaint->status === 'rejected')
                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-lg bg-rose-500/20 text-rose-400 border border-rose-500/30">Rejected</span>
                        @else
                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-lg bg-amber-500/20 text-amber-400 border border-amber-500/30">Pending</span>
                        @endif
                    </div>

                    <h4 class="text-base font-bold text-white">{{ $complaint->title }}</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">{{ $complaint->description }}</p>

                    @if($complaint->response)
                        <div class="mt-3 p-3 rounded-xl bg-slate-950/80 border border-slate-800 text-xs text-slate-300">
                            <span class="font-bold text-emerald-400 block mb-0.5">💬 Tanggapan Tim K3L:</span>
                            {{ $complaint->response }}
                        </div>
                    @endif
                </div>

                <!-- Action Response Form -->
                <div class="w-full md:w-80 shrink-0 bg-slate-950/60 p-4 rounded-2xl border border-slate-800 space-y-3">
                    <span class="text-xs font-bold text-white block">Tanggapi Pengaduan:</span>
                    <form action="{{ route('complaints.respond', $complaint->id) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <select name="status" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white">
                                <option value="pending" {{ $complaint->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ $complaint->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Resolved (Selesai)</option>
                                <option value="rejected" {{ $complaint->status === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                            </select>
                        </div>
                        <div>
                            <textarea name="response" rows="2" required placeholder="Tuliskan jawaban tindak lanjut tim..." class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white">{{ $complaint->response }}</textarea>
                        </div>
                        <button type="submit" class="w-full py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition cursor-pointer">
                            Kirim Tanggapan
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400 text-center py-10">Belum ada pengaduan warga.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $complaints->links() }}
    </div>

</div>
@endsection
