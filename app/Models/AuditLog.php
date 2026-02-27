<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'model_type',
        'model_id',
        'old_data',
        'new_data',
        'remarks',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'performed_at',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'performed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditable()
    {
        return $this->morphTo('model');
    }

    // Scopes for easy filtering
    public function scopeForModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('performed_at', [$start, $end]);
    }
}