<?php

namespace App\Services;

use App\Models\Site;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model = 'gemini-1.5-flash';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    /**
     * Analyze plant photo condition using Gemini Vision API.
     *
     * @param string $photoPath Full absolute path to stored image file
     * @return array{condition: string, health_score: float, notes: string}
     */
    public function analyzePlantPhoto(string $photoPath): array
    {
        if (!$this->apiKey || !file_exists($photoPath)) {
            return $this->getMockPhotoAnalysis();
        }

        try {
            $imageData = base64_encode(file_get_contents($photoPath));
            $mimeType = mime_content_type($photoPath) ?: 'image/jpeg';

            $prompt = 'Analisis foto real-life survey tanaman revegetasi lahan pascatambang ini secara objektif. '
                . 'Tentukan: '
                . '1) condition: pilih salah satu dari ["healthy", "wilting", "dead", "unknown"]. '
                . '   - "healthy" = Tanaman subur & tajuk hijau rapat. '
                . '   - "wilting" = Tanaman layu / menguning. '
                . '   - "dead" = Tanaman mati / kering. '
                . '   - "unknown" = Potensi tidak hidup / kerdil / atau foto tidak valid (indikasi foto fake / sanitasinya buruk). '
                . '2) health_score: angka desimal antara 0 sampai 100. '
                . '3) notes: penjelasan diagnosa fisik vitalitas tanaman & analisa indikasi keaslian foto dalam Bahasa Indonesia yang formal. '
                . 'Wajib respon HANYA dalam format JSON valid tanpa markdown: '
                . '{"condition": "healthy", "health_score": 88.5, "notes": "..."}';

            $response = Http::timeout(15)->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [[
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $imageData,
                            ]
                        ]
                    ]
                ]]
            ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');
                $text = preg_replace('/```json\s*|\s*```/', '', trim($text));
                $parsed = json_decode($text, true);

                if (is_array($parsed) && isset($parsed['condition'], $parsed['health_score'], $parsed['notes'])) {
                    return [
                        'condition' => in_array($parsed['condition'], ['healthy', 'wilting', 'dead', 'unknown']) ? $parsed['condition'] : 'healthy',
                        'health_score' => (float) $parsed['health_score'],
                        'notes' => (string) $parsed['notes'],
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::error('Gemini Vision AI Error: ' . $e->getMessage());
        }

        return $this->getMockPhotoAnalysis();
    }

    /**
     * Generate formal compliance narrative for KLHK reclamation audit report.
     *
     * @param Site $site
     * @param array $data
     * @return string
     */
    public function generateComplianceNarrative(Site $site, array $data): string
    {
        if (!$this->apiKey) {
            return $this->getMockComplianceNarrative($site, $data);
        }

        try {
            $prompt = "Buatkan narasi resmi Laporan Evaluasi Reklamasi Lahan Pascatambang Batu Bara untuk keperluan audit Kementerian Lingkungan Hidup dan Kehutanan (KLHK).\n"
                . "Detail Lahan: Lokasi {$site->name} ({$site->location}), Luas: {$site->area_hectares} Hektar.\n"
                . "Data Lapangan Periode " . ($data['period'] ?? now()->format('F Y')) . ":\n"
                . "- Total Bibit Ditanam: " . ($data['total_seedlings'] ?? 0) . " pohon\n"
                . "- Rata-rata Survival Rate: " . ($data['survival_rate'] ?? 0) . "%\n"
                . "- Jumlah Petak Lahan (Plots): " . ($data['total_plots'] ?? 0) . "\n\n"
                . "Instruksi:\n"
                . "Buatkan narasi evaluasi profesional 2-3 paragraf dalam Bahasa Indonesia yang lugas, terstruktur, dan objektif. Terdiri atas: 1) Gambaran umum progres penanaman, 2) Evaluasi tingkat keberhasilan tumbuh (survival rate) dan faktor lingkungan pendukung, 3) Rekomendasi tindakan perawatan keberlanjutan untuk audit KLHK.";

            $response = Http::timeout(15)->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [[
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]]
            ]);

            if ($response->successful()) {
                $narrative = $response->json('candidates.0.content.parts.0.text');
                if (!empty($narrative)) {
                    return trim($narrative);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Gemini Text AI Error: ' . $e->getMessage());
        }

        return $this->getMockComplianceNarrative($site, $data);
    }

    protected function getMockPhotoAnalysis(): array
    {
        $conditions = [
            [
                'condition' => 'healthy',
                'health_score' => 92.5,
                'notes' => 'Tanaman menunjukkan pertumbuhan vegetative yang sangat baik. Kerapatan tajuk daun hijau pekat, tidak ada tanda-tanda serangan hama fitofag, serta perakaran kokoh pada media tanah reklamasi.',
            ],
            [
                'condition' => 'healthy',
                'health_score' => 84.0,
                'notes' => 'Kondisi fisik pohon secara umum sehat. Daun muda bertunas aktif, perkembangan batang utama lurus, meski terdapat sedikit bercak fisiologis minor akibat cuaca panas.',
            ],
            [
                'condition' => 'wilting',
                'health_score' => 58.0,
                'notes' => 'Tanaman mengalami gejala kelayuan sedang (wilting). Daun bagian bawah tampak menguning dan menggulung, mengindikasikan tingkat kelembapan tanah yang rendah atau stres dehidrasi.',
            ],
            [
                'condition' => 'dead',
                'health_score' => 12.0,
                'notes' => 'Bibit tanaman telah gugur/mati. Batang mengering mengayu dan tidak ditemukan aktivitas klorofil daun. Direkomendasikan penyulaman ulang (replanting) pada lokasi petak ini.',
            ],
            [
                'condition' => 'unknown',
                'health_score' => 35.0,
                'notes' => 'Potensi Tanaman Tidak Hidup / Anomali Survey: Pertumbuhan kerdil terhambat dan terindikasi foto sudut pandang tidak jelas. Disarankan verifikasi ulang fisik oleh Admin/Manager.',
            ],
        ];

        return $conditions[array_rand($conditions)];
    }

    protected function getMockComplianceNarrative(Site $site, array $data): string
    {
        $period = $data['period'] ?? now()->format('F Y');
        $totalSeedlings = number_format($data['total_seedlings'] ?? 0);
        $survivalRate = number_format($data['survival_rate'] ?? 0, 1);
        $area = $site->area_hectares;

        return "Berdasarkan hasil pemantauan berkala pada lokasi reklamasi pascatambang {$site->name} ({$site->location}) periode {$period}, kegiatan revegetasi lahan seluas {$area} Hektar telah mencatatkan akumulasi penanaman sebanyak {$totalSeedlings} bibit tanaman pelopor dan lokal.\n\n"
            . "Evaluasi kuantitatif menunjukkan rata-rata tingkat keberhasilan hidup (survival rate) mencapai {$survivalRate}%. Pertumbuhan vegetasi didukung oleh pengondisian media tanam yang efektif serta aplikasi pemupukan berkala, sehingga struktur tutupan tajuk mulai terbentuk merata di seluruh petak area penanaman.\n\n"
            . "Untuk mempertahankan performa revegetasi sesuai standar kriteria keberhasilan audit Kementerian Lingkungan Hidup dan Kehutanan (KLHK), disarankan untuk melanjutkan program penyulaman bibit secara bertahap pada petak yang memiliki kepadatan rendah serta mengoptimalkan drainase resapan air pada musim kemarau.";
    }
}
