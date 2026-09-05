<?php

namespace App\Repositories;

use App\Models\Site;

class SiteRepository
{
    public function getAllPaginated(int $perPage = 10)
    {
        return Site::with(['manager', 'plots'])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(string $id)
    {
        return Site::findOrFail($id);
    }

    public function getDetailsById(string $id)
    {
        return Site::with([
            'manager',
            'plots.plantingRecords.species',
            'plots.plantingRecords.monitoringLogs.photos',
            'complaints',
            'complianceReports'
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Site::create($data);
    }

    public function update(Site $site, array $data)
    {
        $site->update($data);
        return $site;
    }

    public function delete(Site $site)
    {
        return $site->delete();
    }
}
