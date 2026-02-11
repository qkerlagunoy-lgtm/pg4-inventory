<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    protected $fillable = [
        'requester',
        'purpose',
        'urgency',
        'status',
        'date_requested',
        'date_delivered',
    ];

    public function items()
    {
        return $this->hasMany(OrderRequestItem::class);
    }
}
