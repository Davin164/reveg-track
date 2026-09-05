<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::where('role', 'manager')->first() ?? User::first();

        $sites = [
            [
                'name' => 'Blok Air Laya Utama (TAL)',
                'location' => 'Tanjung Enim, Kabupaten Muara Enim, Sumatera Selatan',
                'area_hectares' => 125.50,
                'status' => 'in_progress',
                'description' => 'Kawasan reklamasi aktif PT Bukit Asam pengganti pit bekas tambang batu bara terintegrasi.',
            ],
            [
                'name' => 'Blok Muara Tiga Besar (MTB)',
                'location' => 'Lawang Kidul, Muara Enim, Sumatera Selatan',
                'area_hectares' => 88.20,
                'status' => 'in_progress',
                'description' => 'Area revegetasi lereng disposal tambang PAMA Persada Nusantara.',
            ],
            [
                'name' => 'Blok Banko Barat Pit 1',
                'location' => 'Kec. Merapi Timur, Lahat, Sumatera Selatan',
                'area_hectares' => 64.75,
                'status' => 'completed',
                'description' => 'Lahan bekas tambang yang telah menyelesaikan tahap penutupan tajuk kanopi hutan pionir.',
            ],
            [
                'name' => 'Blok Keliat Selo',
                'location' => 'Tanjung Enim Selatan, Muara Enim',
                'area_hectares' => 45.00,
                'status' => 'verified',
                'description' => 'Kawasan reklamasi yang telah terverifikasi memenuhi standar ambang batas audit KLHK.',
            ],
            [
                'name' => 'Blok Pit 3 East Extension',
                'location' => 'Kawasan Pertambangan Batu Bara Suban',
                'area_hectares' => 32.10,
                'status' => 'pending',
                'description' => 'Area persiapan pembenahan tanah topsoil dan pemetaan petak tanam baru.',
            ],
        ];

        foreach ($sites as $site) {
            Site::create([
                'id' => (string) Str::uuid(),
                'managed_by' => $manager->id,
                ...$site,
            ]);
        }
    }
}
