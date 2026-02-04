<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
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
    /**
     * Check if category can be deleted (has no items)
     */
    public function canDelete(): bool
    {
        return $this->items()->count() === 0;
    }
    /**
     * Get item count in this category
     */
    public function getItemCountAttribute(): int
    {
        return $this->items()->count();
    } 
    /**
     * Get active item count in this category
     */
    public function getActiveItemCountAttribute(): int
    {
        return $this->items()->where('is_active', true)->count();
    }
}