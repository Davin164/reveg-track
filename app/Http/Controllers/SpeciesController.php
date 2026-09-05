<?php

namespace App\Http\Controllers;

use App\Models\Species;
use Illuminate\Http\Request;

class SpeciesController extends Controller
{
    public function index(Request $request)
    {
        $speciesList = Species::withCount('plantingRecords')->get();

        if ($request->wantsJson()) {
            return response()->json($speciesList);
        }

        return view('species.index', compact('speciesList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'latin_name' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'ideal_condition' => 'nullable|string|max:255',
        ]);

        $species = Species::create($validated);

        if ($request->wantsJson()) {
            return response()->json($species, 201);
        }

        return redirect()->route('species.index')->with('success', 'Jenis pohon berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $species = Species::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'latin_name' => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'ideal_condition' => 'nullable|string|max:255',
        ]);

        $species->update($validated);

        if ($request->wantsJson()) {
            return response()->json($species);
        }

        return redirect()->route('species.index')->with('success', 'Jenis pohon berhasil diperbarui.');
    }

    public function destroy(Request $request, string $id)
    {
        $species = Species::findOrFail($id);
        $species->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Species berhasil dihapus.']);
        }

        return redirect()->route('species.index')->with('success', 'Jenis pohon berhasil dihapus.');
    }
}
