<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitoringLog extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'planting_record_id',
        'logged_by',
        'alive_count',
        'dead_count',
        'survival_rate',
        'ai_condition',
        'ai_health_score',
        'ai_notes',
        'monitored_at',
    ];

    protected function casts(): array
    {
        return [
            'monitored_at' => 'date',
            'alive_count' => 'integer',
            'dead_count' => 'integer',
            'survival_rate' => 'float',
            'ai_health_score' => 'float',
        ];
    }

    public function plantingRecord(): BelongsTo
    {
        return $this->belongsTo(PlantingRecord::class);
    }

    public function logger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
}
