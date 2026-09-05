<?php

namespace App\Http\Controllers;

use App\Models\ComplianceReport;
use App\Models\Site;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplianceReportController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index(Request $request, ?string $site_id = null)
    {
        $query = ComplianceReport::with(['site', 'generator']);

        if ($site_id) {
            $query->where('site_id', $site_id);
        }

        $reports = $query->latest('generated_at')->paginate(10);

        if ($request->wantsJson()) {
            return response()->json($reports);
        }

        $sites = Site::all();

        return view('reports.index', compact('reports', 'sites', 'site_id'));
    }

    public function generate(Request $request, string $site_id)
    {
        $site = Site::with(['plots.plantingRecords.monitoringLogs'])->findOrFail($site_id);

        $period = $request->input('period', now()->format('F Y'));
        $totalSeedlings = $site->totalSeedlingsPlanted();
        $survivalRate = $site->averageSurvivalRate();
        $totalPlots = $site->plots->count();

        $aiNarrative = $this->geminiService->generateComplianceNarrative($site, [
            'period' => $period,
            'total_seedlings' => $totalSeedlings,
            'survival_rate' => $survivalRate,
            'total_plots' => $totalPlots,
        ]);

        $report = ComplianceReport::create([
            'site_id' => $site->id,
            'generated_by' => Auth::id(),
            'title' => "Laporan Reklamasi & Evaluasi KLHK — {$site->name} ({$period})",
            'ai_narrative' => $aiNarrative,
            'period' => $period,
            'status' => 'draft',
            'generated_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json($report, 201);
        }

        return redirect()->route('reports.show', $report->id)->with('success', 'Laporan kepatuhan audit KLHK berhasil dibuat otomatis oleh Gemini AI.');
    }

    public function show(Request $request, string $id)
    {
        $report = ComplianceReport::with(['site.manager', 'site.plots.plantingRecords.species', 'generator'])->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json($report);
        }

        return view('reports.show', compact('report'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $report = ComplianceReport::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,final,submitted',
        ]);

        $report->update(['status' => $validated['status']]);

        if ($request->wantsJson()) {
            return response()->json($report);
        }

        return redirect()->back()->with('success', 'Status laporan audit berhasil diperbarui.');
    }
}
