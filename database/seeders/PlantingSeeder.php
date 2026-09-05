<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\ComplianceReport;
use App\Models\MonitoringLog;
use App\Models\Photo;
use App\Models\PlantingRecord;
use App\Models\Plot;
use App\Models\Species;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlantingSeeder extends Seeder
{
    public function run(): void
    {
        $surveyor = User::where('role', 'surveyor')->first() ?? User::first();
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $plots = Plot::with('site')->get();
        $speciesList = Species::all();

        if ($plots->isEmpty() || $speciesList->isEmpty()) {
            return;
        }

        $samplePhotos = [
            'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1502082553048-f009c37129b9?w=800&auto=format&fit=crop',
        ];

        foreach ($plots->take(12) as $index => $plot) {
            $species = $speciesList->random();
            $seedlings = rand(120, 500);
            $plantedDate = now()->subMonths(rand(2, 10))->format('Y-m-d');

            $record = PlantingRecord::create([
                'id' => (string) Str::uuid(),
                'plot_id' => $plot->id,
                'species_id' => $species->id,
                'recorded_by' => $surveyor->id,
                'seedling_count' => $seedlings,
                'planted_at' => $plantedDate,
                'notes' => "Penanaman bibit unggul {$species->name} di petak {$plot->plot_code} dengan perlakuan pupuk kompos organik 2kg/pohon.",
            ]);

            // Add 2-3 monitoring logs over time
            for ($m = 1; $m <= 3; $m++) {
                $alive = (int) ($seedlings * (rand(82, 98) / 100));
                $dead = $seedlings - $alive;
                $survivalRate = round(($alive / $seedlings) * 100, 2);
                $logDate = date('Y-m-d', strtotime($plantedDate . " +{$m} months"));

                $aiConditions = ['healthy', 'healthy', 'wilting', 'healthy'];
                $aiCond = $aiConditions[array_rand($aiConditions)];
                $aiScore = rand(78, 96) + 0.5;

                $log = MonitoringLog::create([
                    'id' => (string) Str::uuid(),
                    'planting_record_id' => $record->id,
                    'logged_by' => $surveyor->id,
                    'alive_count' => $alive,
                    'dead_count' => $dead,
                    'survival_rate' => $survivalRate,
                    'ai_condition' => $aiCond,
                    'ai_health_score' => $aiScore,
                    'ai_notes' => "Gemini Vision AI Analysis: Tanaman {$species->name} menunjukkan kondisi {$aiCond} dengan kerapatan klorofil tajuk tinggi. Health score: {$aiScore}/100.",
                    'monitored_at' => $logDate,
                ]);

                Photo::create([
                    'id' => (string) Str::uuid(),
                    'monitoring_log_id' => $log->id,
                    'file_path' => $samplePhotos[array_rand($samplePhotos)],
                    'geotag_lat' => '-3.72' . rand(10, 99),
                    'geotag_lng' => '103.78' . rand(10, 99),
                    'taken_at' => $logDate . ' 10:30:00',
                ]);
            }
        }

        // Seed Complaints
        $complaintTitles = [
            'Genangan Air Pasca Hujan di Sempadan Blok Air Laya',
            'Pengenceran Sedimen Saluran Pengelak Lahat',
            'Permohonan Bibit Kayu Putih untuk Komunitas Warga',
            'Evaluasi Debu Jalur Angkut Tambang dekat Area Revegetasi',
            'Kerusakan Pagar Pengaman Petak Tanam Keliat',
        ];

        $siteList = $plots->pluck('site')->unique('id');

        foreach ($complaintTitles as $idx => $title) {
            Complaint::create([
                'id' => (string) Str::uuid(),
                'site_id' => $siteList->random()->id,
                'submitted_by' => null,
                'title' => $title,
                'description' => "Mohon penanganan dan tindak lanjut dari tim lingkungan PT Bukit Asam & PAMA terkait {$title} agar tidak mengganggu kualitas area reklamasi sekitar warga.",
                'status' => $idx % 2 === 0 ? 'resolved' : 'pending',
                'response' => $idx % 2 === 0 ? 'Tim K3L PT Bukit Asam telah melakukan inspeksi lapangan dan perbaikan di lokasi pengaduan.' : null,
            ]);
        }

        // Seed Compliance Reports
        foreach ($siteList->take(3) as $site) {
            ComplianceReport::create([
                'id' => (string) Str::uuid(),
                'site_id' => $site->id,
                'generated_by' => $admin->id,
                'title' => "Laporan Reklamasi & Evaluasi KLHK — {$site->name} (Agustus 2026)",
                'ai_narrative' => "Berdasarkan pemantauan berkala pada lokasi reklamasi pascatambang {$site->name} periode Agustus 2026, kegiatan revegetasi lahan seluas {$site->area_hectares} Hektar telah mencatatkan pertumbuhan vegetasi yang sangat baik dengan rata-rata tingkat keberhasilan hidup mencapai 91.2%.\n\n"
                    . "Pengondisian tanah topsoil dan penyulaman berkesinambungan secara signifikan mendukung pembentukan ekosistem tajuk hutan pionir. Rekomendasi audit KLHK menyarankan pemeliharaan rutin saluran drainase resapan serta penambahan tanaman pengayaan spesies lokal.",
                'period' => 'Agustus 2026',
                'status' => 'final',
                'generated_at' => now(),
            ]);
        }
    }
}
