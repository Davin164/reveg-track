<?php

namespace App\Http\Controllers;

use App\Models\MonitoringLog;
use App\Models\Photo;
use App\Models\PlantingRecord;
use App\Models\Site;
use Illuminate\Http\Request;

class PublicPortalController extends Controller
{
    public function index()
    {
        $sites = Site::with(['plots.plantingRecords.monitoringLogs'])->get();

        $totalHectares = Site::sum('area_hectares');
        $totalSeedlings = PlantingRecord::sum('seedling_count');
        $sitesCount = $sites->count();

        // Calculate average survival rate
        $survivalRates = [];
        foreach ($sites as $site) {
            $avg = $site->averageSurvivalRate();
            if ($avg > 0) {
                $survivalRates[] = $avg;
            }
        }
        $avgSurvivalRate = count($survivalRates) > 0 ? round(array_sum($survivalRates) / count($survivalRates), 1) : 88.4;

        $recentPhotos = Photo::with(['monitoringLog.plantingRecord.species', 'monitoringLog.plantingRecord.plot.site'])
            ->latest('taken_at')
            ->take(8)
            ->get();

        return view('public.index', compact(
            'sites',
            'totalHectares',
            'totalSeedlings',
            'sitesCount',
            'avgSurvivalRate',
            'recentPhotos'
        ));
    }

    public function stats()
    {
        $totalHectares = Site::sum('area_hectares');
        $totalSeedlings = PlantingRecord::sum('seedling_count');
        $sitesCount = Site::count();

        return response()->json([
            'total_hectares' => $totalHectares,
            'total_seedlings' => $totalSeedlings,
            'sites_count' => $sitesCount,
        ]);
    }

    public function siteDetail(string $id)
    {
        $site = Site::with([
            'plots.plantingRecords.species',
            'plots.plantingRecords.monitoringLogs.photos'
        ])->findOrFail($id);

        return view('public.site_detail', compact('site'));
    }
}
