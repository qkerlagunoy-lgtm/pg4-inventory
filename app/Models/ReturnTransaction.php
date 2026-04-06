<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'issuance_item_id',
        'issuance_id',
        'item_id',
        'processed_by',
        'quantity_returned',
        'condition',
        'notes',
        'restocked',
        'returned_at',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
        'restocked'   => 'boolean',
    ];

    public function issuanceItem()
    {
        return $this->belongsTo(IssuanceItem::class);
    }

    public function issuance()
    {
        return $this->belongsTo(Issuance::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}