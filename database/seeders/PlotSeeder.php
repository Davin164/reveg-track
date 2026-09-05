<?php

namespace Database\Seeders;

use App\Models\Plot;
use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlotSeeder extends Seeder
{
    public function run(): void
    {
        $sites = Site::all();

        foreach ($sites as $site) {
            $codes = ['A1', 'A2', 'B1', 'B2', 'C1'];
            foreach ($codes as $index => $code) {
                Plot::create([
                    'id' => (string) Str::uuid(),
                    'site_id' => $site->id,
                    'plot_code' => "PLT-{$code}",
                    'area_m2' => rand(1500, 5000),
                    'status' => $index % 2 === 0 ? 'monitoring' : 'planted',
                ]);
            }
        }
    }
}
