<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'description',
        'is_active',
        'created_by'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // FIX: Missing relationship — required by InventoryController@lowStock
    // and InventoryController@index for category filtering.
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}