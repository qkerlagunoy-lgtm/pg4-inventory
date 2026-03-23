<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function users()
    {
        return $this->hasMany(User::class, 'unit_id');
    }

    public function activeUsers()
    {
        return $this->users()->whereNotNull('email_verified_at');
    }

    public function devices()
    {
        return $this->hasManyThrough(
            DeviceRegistry::class,
            User::class,
            'unit_id',
            'user_id',
        );
    }

    public function offenses()
    {
        return $this->hasManyThrough(
            Offense::class,
            User::class,
            'unit_id',
            'user_id',
        );
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('code');
    }

    // ── Accessors ──────────────────────────────────────────────────

    public function getDisplayNameAttribute(): string
    {
        return $this->name ? "{$this->code} - {$this->name}" : $this->code;
    }

    public function getFullNameAttribute(): string
    {
        $unitNames = [
            'BDCU'    => 'Budget and Disbursement Control Unit',
            'CUI'     => 'Command Unit Interface',
            'COMMAND' => 'Command Center',
            'ISU'     => 'Information Systems Unit',
            'LSO'     => 'Logistics Support Office',
            'PAU'     => 'Planning and Assessment Unit',
            'PG1'     => 'Program Group 1',
            'PG3'     => 'Program Group 3',
            'PG4'     => 'Program Group 4',
            'PG10'    => 'Program Group 10',
            'PPBU'    => 'Policy and Planning Bureau Unit',
            'TFD'     => 'Technical and Financial Division',
        ];

        return $unitNames[$this->code] ?? $this->name ?? $this->code;
    }
}