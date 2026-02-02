<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderRequestItem;

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
    public function orderRequestItems()
{
    return $this->hasMany(OrderRequestItem::class);
}
}
