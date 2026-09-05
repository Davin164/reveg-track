<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\MonitoringLog;
use App\Models\PlantingRecord;
use App\Models\Site;
use App\Models\Species;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sitesCount = Site::count();
        $totalHectares = Site::sum('area_hectares');
        $totalSeedlings = PlantingRecord::sum('seedling_count');
        $speciesCount = Species::count();

        // Calculate average survival rate across all latest monitoring logs
        $latestLogs = MonitoringLog::select('planting_record_id', DB::raw('MAX(monitored_at) as max_date'))
            ->groupBy('planting_record_id');

        $overallSurvivalRate = MonitoringLog::joinSub($latestLogs, 'latest_logs', function ($join) {
            $join->on('monitoring_logs.planting_record_id', '=', 'latest_logs.planting_record_id')
                ->on('monitoring_logs.monitored_at', '=', 'latest_logs.max_date');
        })->avg('survival_rate') ?? 0;

        $pendingComplaintsCount = Complaint::where('status', 'pending')->count();

        // Data for Chart 1: Survival Rate per Site
        $sites = Site::with(['plots.plantingRecords.monitoringLogs'])->get();
        $siteLabels = [];
        $siteSurvivalData = [];

        foreach ($sites as $site) {
            $siteLabels[] = $site->name;
            $siteSurvivalData[] = $site->averageSurvivalRate();
        }

        // Data for Chart 2: Monthly Monitoring Trends (Last 6 Months)
        $driver = DB::getDriverName();
        $monthFormat = $driver === 'sqlite'
            ? "strftime('%Y-%m', monitored_at)"
            : "DATE_FORMAT(monitored_at, '%Y-%m')";

        $monthlyLogs = MonitoringLog::select(
            DB::raw("{$monthFormat} as month_key"),
            DB::raw('AVG(survival_rate) as avg_rate'),
            DB::raw('COUNT(*) as total_logs')
        )
        ->groupBy('month_key')
        ->orderBy('month_key', 'asc')
        ->take(6)
        ->get();

        $monthLabels = $monthlyLogs->pluck('month_key')->map(function ($key) {
            return date('M Y', strtotime($key . '-01'));
        })->toArray();

        $monthSurvivalData = $monthlyLogs->pluck('avg_rate')->map(fn($v) => round((float)$v, 1))->toArray();

        $recentLogs = MonitoringLog::with(['plantingRecord.species', 'plantingRecord.plot.site', 'logger'])
            ->latest('monitored_at')
            ->take(5)
            ->get();

        $recentComplaints = Complaint::with('site')
            ->latest()
            ->take(5)
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'stats' => [
                    'sites_count' => $sitesCount,
                    'total_hectares' => $totalHectares,
                    'total_seedlings' => $totalSeedlings,
                    'species_count' => $speciesCount,
                    'overall_survival_rate' => round($overallSurvivalRate, 1),
                    'pending_complaints' => $pendingComplaintsCount,
                ],
                'chart_site_survival' => [
                    'labels' => $siteLabels,
                    'data' => $siteSurvivalData,
                ],
                'chart_monthly_trend' => [
                    'labels' => $monthLabels,
                    'data' => $monthSurvivalData,
                ]
            ]);
        }

        return view('dashboard.index', compact(
            'sitesCount',
            'totalHectares',
            'totalSeedlings',
            'speciesCount',
            'overallSurvivalRate',
            'pendingComplaintsCount',
            'siteLabels',
            'siteSurvivalData',
            'monthLabels',
            'monthSurvivalData',
            'recentLogs',
            'recentComplaints'
        ));
    }
}
