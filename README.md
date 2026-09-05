<div align="center">
  <h1>🌱 ReVeg Track</h1>

  <p>
    <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
    <img src="https://img.shields.io/badge/PHP_8-777BB4?style=for-the-badge&logo=php&logoColor=white" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" />
    <img src="https://img.shields.io/badge/PostgreSQL-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" />
    <img src="https://img.shields.io/badge/Gemini_AI-8E75B2?style=for-the-badge&logo=googlebard&logoColor=white" />
    <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" />
    <img src="https://img.shields.io/badge/GitHub_Actions-2088FF?style=for-the-badge&logo=githubactions&logoColor=white" />
  </p>

  <p>
    <strong>Platform Monitoring Reklamasi Tambang terintegrasi AI untuk PT *** & ***</strong><br />
  </p>

  <p>
    <img src="https://img.shields.io/badge/Status-Active-success?style=flat-square" />
    <img src="https://img.shields.io/badge/Version-1.0.0-blue?style=flat-square" />
    <img src="https://img.shields.io/badge/GDGoC_UNSRI-Final_Project-orange?style=flat-square" />
  </p>
</div>

---

## 🌟 Overview

**ReVeg Track** adalah platform berbasis web untuk melacak, mengevaluasi, dan mengaudit kelangsungan hidup vegetasi di lahan pascatambang. Aplikasi ini dirancang untuk mendigitalkan proses survei lapangan, mencegah manipulasi laporan, dan meningkatkan transparansi bagi masyarakat. 

Keunggulan utama sistem ini adalah integrasi dengan **Google Gemini 3.6 Flash AI Vision** yang dapat memvalidasi foto lapangan (mendeteksi kesehatan tanaman, kebakaran lahan, hingga indikasi foto palsu) secara otomatis.

Project ini sepenuhnya **dockerized** dan sudah terintegrasi dengan pipeline **CI/CD** (GitHub Actions) yang otomatis mem-build & push image ke **GitHub Container Registry (GHCR)** setiap ada perubahan ke branch `main`.

---

## ✨ Fitur Utama

- 🔐 **Autentikasi Aman** — Login sistem dilindungi oleh enkripsi *Bcrypt* dengan pembagian akses (*Role-Based Access Control*) untuk Admin, Manager, dan Surveyor.
- 📸 **Validasi Vision AI (Gemini)** — Surveyor lapangan mengunggah foto vegetasi, lalu AI akan menganalisis kondisi daun, batang, dan lingkungan secara *real-time* untuk memberikan *Health Score* dan mendeteksi anomali (seperti kebakaran atau manipulasi gambar).
- 🌍 **Portal Informasi Publik (Tanpa Login)** — Warga dapat melihat transparansi data reklamasi, total area, *survival rate*, dan galeri progres foto secara langsung.
- 📢 **Sistem Pengaduan Warga** — Masyarakat sekitar tambang dapat melapor/mengirim keluhan langsung lewat portal, yang kemudian dapat direspon oleh Admin PT ***. 
- 📊 **Dashboard Analitik & Tren** — Visualisasi data (menggunakan Chart.js) yang menampilkan grafik tingkat keberhasilan tumbuh (*survival rate*) dan tren pemantauan bulanan.
- 🌳 **Manajemen Lahan & Plot** — Pengelompokan area tambang (Sites) dan titik penanaman (Plots) yang terstruktur.
- 🛡️ **Keamanan & Optimasi** — Mencegah *N+1 Query Problem* dengan Eager Loading, menggunakan `DB::transaction` untuk menjaga integritas data, serta validasi input yang ketat lewat Form Requests.

---

## 🛠️ Tech Stack

| Kategori | Teknologi |
|---|---|
| Framework Backend | Laravel 11 (PHP 8.2) |
| Frontend | Blade Template, Tailwind CSS, Chart.js |
| Database | PostgreSQL (bisa dikonfigurasi ke MySQL) |
| Artificial Intelligence | Google Gemini API (Model: `gemini-3.6-flash`) |
| Arsitektur | MVC, Repository-Service Pattern |
| Containerization | Docker, Docker Compose (Multi-stage build) |
| CI/CD | GitHub Actions → GHCR |

---

## 📋 Daftar Isi

1. [Environment Configuration](#️-environment-configuration)
2. [Cara Menjalankan Aplikasi](#-cara-menjalankan-aplikasi)
   - [Mode A — Docker Development (Lokal)](#mode-a--docker-development-lokal)
   - [Mode B — Docker Production](#mode-b--docker-production)
3. [Project Structure](#-project-structure)
4. [Daftar Role Akun (Seeder)](#-daftar-role-akun-seeder)
5. [CI/CD](#-cicd)
6. [Kriteria Penilaian GDGoC UNSRI](#-kriteria-penilaian-gdgoc-unsri)

---

## ⚙️ Environment Configuration

Copy `.env.example` menjadi `.env`, lalu isi semua value:

```bash
cp .env.example .env
```

| Variabel | Deskripsi |
|---|---|
| `APP_ENV` | `local` atau `production`. |
| `DB_CONNECTION` / `DB_HOST` | Konfigurasi database. Untuk Docker gunakan host `db`. |
| `GEMINI_API_KEY` | Kunci API Google Gemini (Dapatkan gratis di Google AI Studio). **Wajib diisi agar fitur Vision AI bekerja.** |

> ⚠️ **Catatan Docker:** `docker-compose.yml` (production) dan `docker-compose.dev.yml` (development) secara default mengatur *environment* database secara eksplisit, menimpa file `.env` lokal untuk meminimalisir konflik koneksi saat menggunakan *container*.

---

## 🚀 Cara Menjalankan Aplikasi

### Mode A — Docker Development (Lokal)

Menjalankan aplikasi + PostgreSQL di dalam container, dengan *hot-reload/volume mount* untuk development.

**Prerequisites:** Docker & Docker Compose.

```bash
git clone https://github.com/Username/reveg-track.git
cd reveg-track
cp .env.example .env   # Jangan lupa isi GEMINI_API_KEY

docker compose -f docker-compose.dev.yml up -d --build
```

Setelah container berjalan, lakukan *migration* & instalasi *dependency* (jika belum):

```bash
docker exec -it reveg-track-app-dev composer install
docker exec -it reveg-track-app-dev php artisan key:generate
docker exec -it reveg-track-app-dev php artisan migrate:fresh --seed
```

Aplikasi berjalan di `http://localhost:8000`. Untuk mematikan:

```bash
docker compose -f docker-compose.dev.yml down
```

### Mode B — Docker Production

Menjalankan **image production** berbasis web server Apache yang sudah di-*build* efisien menggunakan arsitektur *multi-stage*.

```bash
docker compose -f docker-compose.yml up -d --build
```

Aplikasi berjalan di `http://localhost:80`.

---

## 📁 Project Structure

```
reveg-track/
├── .github/workflows/       # CI/CD (GitHub Actions ke GHCR)
├── app/
│   ├── Http/Controllers/    # Endpoint & UI logic
│   ├── Models/              # Representasi Database & Relasi Eloquent
│   ├── Services/            # Business Logic Eksternal (GeminiService.php)
│   └── Http/Requests/       # Form Validation
├── database/
│   ├── migrations/          # Riwayat skema database
│   └── seeders/             # Dummy data & pembuatan akun awal
├── resources/
│   └── views/               # Blade Templates (Admin Dashboard & Public Portal)
├── Dockerfile               # Production image (multi-stage PHP & Node)
├── Dockerfile.dev           # Development image
├── docker-compose.yml       # Production Compose
├── docker-compose.dev.yml   # Development Compose
└── .dockerignore            # Pengecualian build image
```

---

## 🔑 Daftar Role Akun (Seeder)

Gunakan kredensial berikut untuk masuk ke sistem *internal dashboard* (Role `Admin`, `Manager`, dan `Surveyor` memiliki hak akses menu yang berbeda):

| Role / Nama Akun | Email | Password |
|---|---|---|
| **Administrator PT *** | `admin@reveg.test` | `password123` |
| **Manager Reklamasi ***** | `manager@reveg.test` | `password123` |
| **Surveyor Lapangan** | `surveyor@reveg.test` | `password123` |

Akses **Portal Publik Warga** dapat dilihat langsung tanpa proses login melalui rute halaman utama `/`.

---

## ⚡ CI/CD

Setiap *push* ke branch `main`, GitHub Actions otomatis:

1. Melakukan *Checkout* kode.
2. Login ke **GitHub Container Registry (GHCR)**.
3. Melakukan *build* image `Dockerfile` menggunakan *cache* untuk mempercepat proses.
4. Mendorong (*Push*) image yang telah di-build dengan tag `:latest`.

Image terbaru dapat ditarik dengan:
```bash
docker pull ghcr.io/username/reveg-track:latest
```

By: Davin Backend Dev - GDGoC Unsri

<br>

<div align="center">
  <sub>Dikembangkan untuk Final Project GDGoC UNSRI 2026</sub>
</div>
