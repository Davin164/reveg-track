<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'monitoring_log_id',
        'file_path',
        'geotag_lat',
        'geotag_lng',
        'taken_at',
    ];

    protected function casts(): array
    {
        return [
            'taken_at' => 'datetime',
        ];
    }

    public function monitoringLog(): BelongsTo
    {
        return $this->belongsTo(MonitoringLog::class);
    }
}
