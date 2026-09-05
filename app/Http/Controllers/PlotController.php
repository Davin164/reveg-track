<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\Site;
use Illuminate\Http\Request;

class PlotController extends Controller
{
    public function index(Request $request, ?string $site_id = null)
    {
        $query = Plot::with(['site', 'plantingRecords.species']);

        if ($site_id) {
            $query->where('site_id', $site_id);
        }

        $plots = $query->paginate(15);

        if ($request->wantsJson()) {
            return response()->json($plots);
        }

        $sites = Site::all();
        return view('plots.index', compact('plots', 'sites', 'site_id'));
    }

    public function store(Request $request, ?string $site_id = null)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'plot_code' => 'required|string|max:50',
            'area_m2' => 'required|numeric|min:1',
            'status' => 'required|in:empty,planted,monitoring,completed',
        ]);

        $plot = Plot::create($validated);

        if ($request->wantsJson()) {
            return response()->json($plot, 201);
        }

        return redirect()->back()->with('success', 'Petak lahan berhasil ditambahkan.');
    }

    public function show(Request $request, string $id)
    {
        $plot = Plot::with(['site', 'plantingRecords.species', 'plantingRecords.monitoringLogs.photos'])->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json($plot);
        }

        return view('plots.show', compact('plot'));
    }

    public function update(Request $request, string $id)
    {
        $plot = Plot::findOrFail($id);

        $validated = $request->validate([
            'plot_code' => 'required|string|max:50',
            'area_m2' => 'required|numeric|min:1',
            'status' => 'required|in:empty,planted,monitoring,completed',
        ]);

        $plot->update($validated);

        if ($request->wantsJson()) {
            return response()->json($plot);
        }

        return redirect()->back()->with('success', 'Petak lahan berhasil diperbarui.');
    }

    public function destroy(Request $request, string $id)
    {
        $plot = Plot::findOrFail($id);
        $plot->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Plot berhasil dihapus.']);
        }

        return redirect()->back()->with('success', 'Petak lahan berhasil dihapus.');
    }
}
