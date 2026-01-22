<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'item_name',
        'description',
        'available_quantity',
    ];

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }
}