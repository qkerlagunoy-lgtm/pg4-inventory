<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceOffense extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_registry_id',
        'title',
        'description',
        'offense_date',
        'status',
        'filed_by',
    ];

    protected $casts = [
        'offense_date' => 'date',
    ];

    public function device()
    {
        return $this->belongsTo(DeviceRegistry::class, 'device_registry_id');
    }

    public function filedBy()
    {
        return $this->belongsTo(User::class, 'filed_by');
    }
}