<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Informasi Reklamasi Lahan — PT Bukit Asam & PAMA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full text-slate-100 bg-slate-950 font-sans antialiased flex flex-col justify-between">
    <!-- Navbar -->
    <header class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C20.832 18.477 19.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">ReVeg <span class="text-emerald-400">Track</span></h1>
                    <p class="text-[11px] text-slate-400 font-semibold uppercase">Portal Reklamasi Tambang Batu Bara</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <a href="#stats" class="text-sm font-medium text-slate-300 hover:text-emerald-400 transition hidden md:block">Capaian</a>
                <a href="#sites" class="text-sm font-medium text-slate-300 hover:text-emerald-400 transition hidden md:block">Blok Lahan</a>
                <a href="#complaint" class="text-sm font-medium text-slate-300 hover:text-emerald-400 transition hidden md:block">Form Pengaduan</a>
                <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold bg-emerald-500 hover:bg-emerald-400 text-slate-950 rounded-xl shadow-lg shadow-emerald-500/20 transition">
                    Login Staff/Admin
                </a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 py-10 mt-16">
        <div class="max-w-7xl mx-auto px-6 text-center text-slate-400 text-xs">
            <p class="font-semibold text-slate-300 text-sm mb-2">ReVeg Track — PT Bukit Asam Tbk & PAMA</p>
            <p>Sistem Pemantauan Transparansi Reklamasi Lahan Pascatambang Batu Bara Tanjung Enim, Sumatera Selatan.</p>
            <p class="mt-4 text-slate-500">&copy; 2026 PT Bukit Asam & PAMA. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
