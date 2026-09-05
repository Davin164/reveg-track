<?php

namespace App\Http\Controllers;

use App\Models\PlantingRecord;
use App\Models\Plot;
use App\Models\Species;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlantingRecordController extends Controller
{
    public function index(Request $request, ?string $plot_id = null)
    {
        $query = PlantingRecord::with(['plot.site', 'species', 'recorder', 'monitoringLogs']);

        if ($plot_id) {
            $query->where('plot_id', $plot_id);
        }

        $records = $query->latest('planted_at')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json($records);
        }

        $plots = Plot::with('site')->get();
        $speciesList = Species::all();

        return view('planting.index', compact('records', 'plots', 'speciesList', 'plot_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plot_id' => 'required|exists:plots,id',
            'species_id' => 'required|exists:species,id',
            'seedling_count' => 'required|integer|min:1',
            'planted_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['recorded_by'] = Auth::id();

        $record = PlantingRecord::create($validated);

        // Update plot status to planted
        $plot = Plot::find($validated['plot_id']);
        if ($plot) {
            $plot->update(['status' => 'planted']);
        }

        if ($request->wantsJson()) {
            return response()->json($record, 201);
        }

        return redirect()->back()->with('success', 'Catatan kegiatan tanam berhasil disimpan.');
    }

    public function show(Request $request, string $id)
    {
        $record = PlantingRecord::with(['plot.site', 'species', 'recorder', 'monitoringLogs.photos'])->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json($record);
        }

        return view('planting.show', compact('record'));
    }
}
