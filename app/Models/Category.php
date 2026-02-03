<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'code',
        'is_active',
        'created_by',
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}