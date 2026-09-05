<?php

namespace Database\Seeders;

use App\Models\Species;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpeciesSeeder extends Seeder
{
    public function run(): void
    {
        $species = [
            [
                'name' => 'Akasia Fastigiata',
                'latin_name' => 'Acacia mangium',
                'description' => 'Tanaman pionir cepat tumbuh, sangat toleran tanah masam lahan tambang dan efektif mengikat nitrogen tanah.',
                'ideal_condition' => 'pH Tanah 4.5 - 6.5, Curah hujan > 1500mm/tahun',
            ],
            [
                'name' => 'Sengon Laut',
                'latin_name' => 'Paraserianthes falcataria',
                'description' => 'Pohon kanopi peneduh cepat tumbuh untuk mempercepat penutupan tajuk reklamasi pascatambang.',
                'ideal_condition' => 'Ketinggian 0-800 mdpl, Drenase tanah memadai',
            ],
            [
                'name' => 'Kayu Putih',
                'latin_name' => 'Melaleuca cajuputi',
                'description' => 'Spesies lokal tahan panas, sangat mampu bertahan di lahan bekas tambang yang minim unsur hara.',
                'ideal_condition' => 'Tahan tergenang & kekeringan ekstrim',
            ],
            [
                'name' => 'Meranti Merah',
                'latin_name' => 'Shorea leprosula',
                'description' => 'Tanaman klimaks lokal Sumatera Selatan yang ditanam sebagai tahap pengayaan ekosistem hutan alam.',
                'ideal_condition' => 'Naungan tajuk awal, tanah kaya lempung berpasir',
            ],
            [
                'name' => 'Eukaliptus',
                'latin_name' => 'Eucalyptus deglupta',
                'description' => 'Pohon perintis berbatang pelangi dengan perakaran dalam untuk stabilisasi lereng bekas galian.',
                'ideal_condition' => 'Sinar matahari penuh, lereng berm reklamasi',
            ],
            [
                'name' => 'Spathodea',
                'latin_name' => 'Spathodea campanulata',
                'description' => 'Pohon berbunga oranye yang menarik serangga penyerbuk dan fauna lokal kembali ke kawasan reklamasi.',
                'ideal_condition' => 'Tanah terbuka, iklim tropis lembab',
            ],
        ];

        foreach ($species as $item) {
            Species::create([
                'id' => (string) Str::uuid(),
                ...$item,
            ]);
        }
    }
}
