<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the users belonging to this unit.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'unit', 'code');
    }

    /**
     * Get active users in this unit.
     */
    public function activeUsers()
    {
        return $this->users()->whereNotNull('email_verified_at');
    }

    /**
     * Scope a query to only include active units.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by the specified order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('code');
    }

    /**
     * Get the formatted display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ? "{$this->code} - {$this->name}" : $this->code;
    }

    /**
     * Get the full unit name with description.
     */
    public function getFullNameAttribute(): string
    {
        $unitNames = [
            'BDCU' => 'Budget and Disbursement Control Unit',
            'CUI' => 'Command Unit Interface',
            'COMMAND' => 'Command Center',
            'ISU' => 'Information Systems Unit',
            'LSO' => 'Logistics Support Office',
            'PAU' => 'Planning and Assessment Unit',
            'PG1' => 'Program Group 1',
            'PG3' => 'Program Group 3',
            'PG4' => 'Program Group 4',
            'PG10' => 'Program Group 10',
            'PPBU' => 'Policy and Planning Bureau Unit',
            'TFD' => 'Technical and Financial Division',
        ];

        return $unitNames[$this->code] ?? $this->name ?? $this->code;
    }
}