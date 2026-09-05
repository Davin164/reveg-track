<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Models\User;
use App\Services\SiteService;
use Illuminate\Http\Request;
use Exception;

class SiteController extends Controller
{
    protected SiteService $siteService;

    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }

    public function index(Request $request)
    {
        try {
            $sites = $this->siteService->getAllSites(10);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $sites
                ]);
            }

            return view('sites.index', compact('sites'));
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        $managers = User::whereIn('role', ['admin', 'manager'])->get();
        return view('sites.create', compact('managers'));
    }

    public function store(StoreSiteRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $site = $this->siteService->createSite($validatedData);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lokasi reklamasi berhasil ditambahkan.',
                    'data' => $site
                ], 201);
            }

            return redirect()->route('sites.show', $site->id)->with('success', 'Lokasi reklamasi berhasil ditambahkan.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Request $request, string $id)
    {
        try {
            $site = $this->siteService->getSiteDetails($id);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $site
                ]);
            }

            return view('sites.show', compact('site'));
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
            }
            return redirect()->route('sites.index')->with('error', $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $site = $this->siteService->getSiteById($id);
            $managers = User::whereIn('role', ['admin', 'manager'])->get();
            return view('sites.edit', compact('site', 'managers'));
        } catch (Exception $e) {
            return redirect()->route('sites.index')->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        // Validation could also be moved to an UpdateSiteRequest for cleaner code
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'area_hectares' => 'required|numeric|min:0.01',
            'status' => 'required|in:pending,in_progress,completed,verified',
            'managed_by' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ]);

        try {
            $site = $this->siteService->updateSite($id, $validatedData);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data lokasi reklamasi berhasil diperbarui.',
                    'data' => $site
                ]);
            }

            return redirect()->route('sites.show', $site->id)->with('success', 'Data lokasi reklamasi berhasil diperbarui.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $this->siteService->deleteSite($id);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Site berhasil dihapus.'
                ]);
            }

            return redirect()->route('sites.index')->with('success', 'Lokasi reklamasi berhasil dihapus.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
