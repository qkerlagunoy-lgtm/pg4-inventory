<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable = [
        'item_request_id',
        'item_id',
        'quantity',
        'unit_of_measure',
        'remarks',
        'status',
    ];

    /**
     * Get the parent item request
     */
    public function itemRequest()
    {
        return $this->belongsTo(ItemRequest::class);
    }

    /**
     * Get the item details
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}