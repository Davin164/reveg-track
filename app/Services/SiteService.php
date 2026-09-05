<?php

namespace App\Services;

use App\Repositories\SiteRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class SiteService
{
    protected SiteRepository $siteRepository;

    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    public function getAllSites(int $perPage = 10)
    {
        try {
            return $this->siteRepository->getAllPaginated($perPage);
        } catch (Exception $e) {
            throw new Exception("Gagal mengambil data lokasi: " . $e->getMessage());
        }
    }

    public function getSiteDetails(string $id)
    {
        try {
            return $this->siteRepository->getDetailsById($id);
        } catch (Exception $e) {
            throw new Exception("Gagal mengambil detail lokasi: " . $e->getMessage());
        }
    }
    
    public function getSiteById(string $id)
    {
        return $this->siteRepository->findById($id);
    }

    public function createSite(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                return $this->siteRepository->create($data);
            });
        } catch (Exception $e) {
            throw new Exception("Gagal membuat lokasi baru: " . $e->getMessage());
        }
    }

    public function updateSite(string $id, array $data)
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $site = $this->siteRepository->findById($id);
                return $this->siteRepository->update($site, $data);
            });
        } catch (Exception $e) {
            throw new Exception("Gagal memperbarui lokasi: " . $e->getMessage());
        }
    }

    public function deleteSite(string $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $site = $this->siteRepository->findById($id);
                return $this->siteRepository->delete($site);
            });
        } catch (Exception $e) {
            throw new Exception("Gagal menghapus lokasi: " . $e->getMessage());
        }
    }
}
