<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Site extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'managed_by',
        'name',
        'location',
        'area_hectares',
        'status',
        'description',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class);
    }

    public function plantingRecords(): HasManyThrough
    {
        return $this->hasManyThrough(PlantingRecord::class, Plot::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function complianceReports(): HasMany
    {
        return $this->hasMany(ComplianceReport::class);
    }

    public function averageSurvivalRate(): float
    {
        $rates = [];
        foreach ($this->plots as $plot) {
            foreach ($plot->plantingRecords as $record) {
                $latestLog = $record->monitoringLogs()->latest('monitored_at')->first();
                if ($latestLog) {
                    $rates[] = (float) $latestLog->survival_rate;
                }
            }
        }

        if (count($rates) === 0) {
            return 0.0;
        }

        return round(array_sum($rates) / count($rates), 2);
    }

    public function totalSeedlingsPlanted(): int
    {
        $total = 0;
        foreach ($this->plots as $plot) {
            $total += $plot->plantingRecords()->sum('seedling_count');
        }
        return $total;
    }
}
