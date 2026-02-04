<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable = [
        'item_request_id',
        'item_id',
        'quantity',
        'remarks',
        'status',
    ];
    protected $casts = [
        'status' => 'string',
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
    /**
     * Check if request item is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    /**
     * Check if request item is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    /**
     * Check if request item is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}