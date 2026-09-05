<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager' || $this->role === 'admin';
    }

    public function isSurveyor(): bool
    {
        return $this->role === 'surveyor' || $this->role === 'manager' || $this->role === 'admin';
    }

    public function isFieldStaff(): bool
    {
        return $this->isSurveyor();
    }

    public function sitesManaged(): HasMany
    {
        return $this->hasMany(Site::class, 'managed_by');
    }

    public function plantingRecords(): HasMany
    {
        return $this->hasMany(PlantingRecord::class, 'recorded_by');
    }

    public function monitoringLogs(): HasMany
    {
        return $this->hasMany(MonitoringLog::class, 'logged_by');
    }

    public function complaintsSubmitted(): HasMany
    {
        return $this->hasMany(Complaint::class, 'submitted_by');
    }

    public function complianceReportsGenerated(): HasMany
    {
        return $this->hasMany(ComplianceReport::class, 'generated_by');
    }
}
