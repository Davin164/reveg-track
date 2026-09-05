<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Species extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'latin_name',
        'description',
        'ideal_condition',
    ];

    public function plantingRecords(): HasMany
    {
        return $this->hasMany(PlantingRecord::class);
    }
}
