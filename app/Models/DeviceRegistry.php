<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceRegistry extends Model
{
    use HasFactory;

    protected $table = 'device_registry';

    protected $fillable = [
        'user_id',
        // ── Plain text personnel fields ──────────────────────────
        'assigned_firstname',
        'assigned_middlename',
        'assigned_lastname',
        'assigned_rank',
        'assigned_unit',
        'assigned_category',
        'assigned_designation',
        'profile_picture',
        // ── Device fields ────────────────────────────────────────
        'ip_address',
        'mac_address',
        'device_name',
        'device_type',
        'serial_number',
        'image',
        'remarks',
        'date_registered',
        'is_active',
        'registered_by',
        'ip_address_range_id',
    ];

    protected $casts = [
        'date_registered' => 'date',
        'is_active'       => 'boolean',
    ];

    // Optional: still link to system user if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    // Offenses linked to this device record
    public function offenses()
    {
        return $this->hasMany(DeviceOffense::class);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    // Helper: full name
    public function getFullNameAttribute(): string
    {
        return trim("{$this->assigned_lastname}, {$this->assigned_firstname} {$this->assigned_middlename}");
    }
}