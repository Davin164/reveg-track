<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMonitoringRequest;
use App\Models\MonitoringLog;
use App\Models\Photo;
use App\Models\PlantingRecord;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonitoringLogController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function index(Request $request, ?string $record_id = null)
    {
        $query = MonitoringLog::with(['plantingRecord.plot.site', 'plantingRecord.species', 'logger', 'photos']);

        if ($record_id) {
            $query->where('planting_record_id', $record_id);
        }

        $logs = $query->latest('monitored_at')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json($logs);
        }

        $plantingRecords = PlantingRecord::with(['plot.site', 'species'])->get();

        return view('monitoring.index', compact('logs', 'plantingRecords', 'record_id'));
    }

    public function create()
    {
        $plantingRecords = PlantingRecord::with(['plot.site', 'species'])->get();
        return view('monitoring.create', compact('plantingRecords'));
    }

    public function store(StoreMonitoringRequest $request)
    {
        $validated = $request->validated();

        $alive = (int) $validated['alive_count'];
        $dead = (int) $validated['dead_count'];
        $total = $alive + $dead;
        $survivalRate = $total > 0 ? round(($alive / $total) * 100, 2) : 0.0;

        $log = MonitoringLog::create([
            'planting_record_id' => $validated['planting_record_id'],
            'logged_by' => Auth::id(),
            'alive_count' => $alive,
            'dead_count' => $dead,
            'survival_rate' => $survivalRate,
            'monitored_at' => $validated['monitored_at'],
        ]);

        // Process photo upload & trigger Gemini Vision AI analysis
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = uniqid('plant_') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('photos', $filename, 'public');
            $fullPath = storage_path('app/public/' . $path);

            // Trigger Gemini Vision AI
            $aiResult = $this->geminiService->analyzePlantPhoto($fullPath);

            $log->update([
                'ai_condition' => $aiResult['condition'],
                'ai_health_score' => $aiResult['health_score'],
                'ai_notes' => $aiResult['notes'],
            ]);

            Photo::create([
                'monitoring_log_id' => $log->id,
                'file_path' => 'storage/' . $path,
                'geotag_lat' => $validated['geotag_lat'] ?? '-3.7225',
                'geotag_lng' => $validated['geotag_lng'] ?? '103.7852',
                'taken_at' => now(),
            ]);
        }

        // Update plot status to monitoring
        $record = PlantingRecord::find($validated['planting_record_id']);
        if ($record && $record->plot) {
            $record->plot->update(['status' => 'monitoring']);
        }

        if ($request->wantsJson()) {
            return response()->json($log->load('photos'), 201);
        }

        return redirect()->route('monitoring.index')->with('success', 'Data monitoring & analisis Vision AI berhasil disimpan.');
    }

    public function uploadPhoto(Request $request, string $id)
    {
        $log = MonitoringLog::findOrFail($id);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240',
            'geotag_lat' => 'nullable|string',
            'geotag_lng' => 'nullable|string',
        ]);

        $file = $request->file('photo');
        $filename = uniqid('plant_') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('photos', $filename, 'public');
        $fullPath = storage_path('app/public/' . $path);

        $aiResult = $this->geminiService->analyzePlantPhoto($fullPath);

        $log->update([
            'ai_condition' => $aiResult['condition'],
            'ai_health_score' => $aiResult['health_score'],
            'ai_notes' => $aiResult['notes'],
        ]);

        $photo = Photo::create([
            'monitoring_log_id' => $log->id,
            'file_path' => 'storage/' . $path,
            'geotag_lat' => $request->input('geotag_lat', '-3.7225'),
            'geotag_lng' => $request->input('geotag_lng', '103.7852'),
            'taken_at' => now(),
        ]);

        return response()->json([
            'message' => 'Foto berhasil diunggah & dianalisis Vision AI.',
            'log' => $log->fresh(['photos']),
            'photo' => $photo,
        ]);
    }

    public function show(Request $request, string $id)
    {
        $log = MonitoringLog::with(['plantingRecord.plot.site', 'plantingRecord.species', 'logger', 'photos'])->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json($log);
        }

        return view('monitoring.show', compact('log'));
    }
}
