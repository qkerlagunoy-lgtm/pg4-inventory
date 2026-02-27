<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RequestItem extends Model
{
    protected $table = 'request_items';

    protected $fillable = [
        'item_request_id',
        'item_id',
        'quantity',
        'approved_quantity',
        'status', // pending, approved, rejected, partially_approved
        'remarks',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'approved_quantity' => 'integer',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function itemRequest()
    {
        return $this->belongsTo(ItemRequest::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function issuanceItems()
    {
        return $this->hasMany(IssuanceItem::class, 'request_item_id');
    }

    // Status check methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected'; 
    }

    // Quantity helper methods
    public function getApprovedQuantity(): int
    {
        return $this->approved_quantity ?? $this->quantity;
    }

    public function getTotalIssuedQuantity(): int
    {
        return $this->issuanceItems()->sum('quantity_issued');
    }

    public function getTotalReturnedQuantity(): int
    {
        return $this->issuanceItems()->sum('quantity_returned');
    }

    public function getRemainingToIssue(): int
    {
        $approved = $this->getApprovedQuantity();
        $issued = $this->getTotalIssuedQuantity();
        return max(0, $approved - $issued);
    }

    public function getRemainingToReturn(): int
    {
        $issued = $this->getTotalIssuedQuantity();
        $returned = $this->getTotalReturnedQuantity();
        return max(0, $issued - $returned);
    }

    public function canIssue(int $quantity): bool
    {
        return $quantity <= $this->getRemainingToIssue();
    }

    public function canReturn(int $quantity): bool
    {
        return $quantity <= $this->getRemainingToReturn();
    }

    // Issuance status derived from related issuance items
    public function getIssuanceStatusAttribute(): string
    {
        $issued = $this->getTotalIssuedQuantity();
        $approved = $this->getApprovedQuantity();

        if ($issued === 0) {
            return 'not_issued';
        } elseif ($issued >= $approved) {
            return 'fully_issued';
        } else {
            return 'partially_issued';
        }
    }

    // Return status derived from related issuance items
    public function getReturnStatusAttribute(): string
    {
        $issued = $this->getTotalIssuedQuantity();
        $returned = $this->getTotalReturnedQuantity();

        if ($returned === 0) {
            return 'not_returned';
        } elseif ($returned >= $issued) {
            return 'fully_returned';
        } else {
            return 'partially_returned';
        }
    }

    // Check if any issued items are overdue
    public function hasOverdueItems(): bool
    {
        return $this->issuanceItems()
            ->where('status', 'issued')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereRaw('quantity_returned < quantity_issued')
            ->exists();
    }
}