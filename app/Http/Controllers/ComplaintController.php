<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintRequest;
use App\Models\Complaint;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $complaints = Complaint::with(['site', 'submitter'])
            ->latest()
            ->paginate(15);

        if ($request->wantsJson()) {
            return response()->json($complaints);
        }

        return view('complaints.index', compact('complaints'));
    }

    public function store(StoreComplaintRequest $request)
    {
        $validated = $request->validated();

        $complaint = Complaint::create([
            'site_id' => $validated['site_id'],
            'submitted_by' => Auth::check() ? Auth::id() : null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        if ($request->wantsJson()) {
            return response()->json($complaint, 201);
        }

        return redirect()->back()->with('success', 'Pengaduan keluhan Anda berhasil dikirim. Terima kasih atas kepedulian Anda terhadap kelestarian lingkungan!');
    }

    public function respond(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,rejected',
            'response' => 'required|string|max:2000',
        ]);

        $complaint->update([
            'status' => $validated['status'],
            'response' => $validated['response'],
        ]);

        if ($request->wantsJson()) {
            return response()->json($complaint);
        }

        return redirect()->back()->with('success', 'Tanggapan pengaduan berhasil diperbarui.');
    }
}
