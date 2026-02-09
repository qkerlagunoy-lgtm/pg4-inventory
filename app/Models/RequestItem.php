<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RequestItem extends Model
{
    protected $fillable = [
        'item_request_id',
        'item_id',
        'quantity',
        'approved_quantity',
        'issued_quantity',
        'returned_quantity',
        'issuance_status',
        'issue_date',
        'due_date',
        'return_date',
        'condition_on_return', 
        'unit_cost_at_time',  
        'total_cost',          
        'issuance_id',
        'remarks',
        'status',
    ];
    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'unit_cost_at_time' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'status' => 'string',
        'issuance_status' => 'string',
        'condition_on_return' => 'string',
    ];
    
    // Boot the model
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($requestItem) {
            // Auto-calculate costs when saving
            $requestItem->calculateCosts();
            // Auto-set issuance status based on quantities
            if ($requestItem->approved_quantity !== null) {
                if ($requestItem->issued_quantity == 0) {
                    $requestItem->issuance_status = 'not_issued';
                } elseif ($requestItem->issued_quantity == $requestItem->approved_quantity) {
                    $requestItem->issuance_status = 'fully_issued';
                } else {
                    $requestItem->issuance_status = 'partially_issued';
                }
            }
        });
    }
    // Get the parent item request
    public function itemRequest()
    {
        return $this->belongsTo(ItemRequest::class);
    }
    // Get the item details
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    //Get the issuance record
    public function issuance()
    {
        return $this->belongsTo(Issuance::class);
    }
    // Check if request item is pending
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    // Check if request item is approved
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    // Check if request item is rejected
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
    public function calculateCosts()
    {
        if ($this->item && $this->item->unit_cost) {
            $this->unit_cost_at_time = (string) $this->item->unit_cost;
            $this->total_cost = (string) ($this->unit_cost_at_time * $this->quantity);
        } else {
            $this->unit_cost_at_time = null;
            $this->total_cost = null;
        }
    }
    // Check if item is overdue
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date < now() && $this->issuance_status === 'issued' &&
               $this->returned_quantity < $this->issued_quantity;
    }
    // Get remaining quantity to issue
    public function getRemainingToIssue(): int
    {
        if ($this->approved_quantity === null) {
            return 0;
        }
        return max(0, $this->approved_quantity - $this->issued_quantity);
    }
    // Get remaining quantity to return
    public function getRemainingToReturn(): int
    {
        return max(0, $this->issued_quantity - $this->returned_quantity);
    }
    // Check if item can be issued
    public function canIssue(int $quantity): bool
    {
        if ($this->approved_quantity === null) {
            return false;
        }
        
        $remainingToIssue = $this->getRemainingToIssue();
        return $quantity <= $remainingToIssue;
    }
    // Check if item can be returned
    public function canReturn(int $quantity): bool
    {
        $remainingToReturn = $this->getRemainingToReturn();
        return $quantity <= $remainingToReturn;
    }
    // Issue item quantity
    public function issueQuantity(int $quantity): bool
    {
        if (!$this->canIssue($quantity)) {
            return false;
        }
        $this->issued_quantity += $quantity;
        // Update issuance status
        if ($this->issued_quantity == 0) {
            $this->issuance_status = 'not_issued';
        } elseif ($this->issued_quantity == $this->approved_quantity) {
            $this->issuance_status = 'fully_issued';
        } else {
            $this->issuance_status = 'partially_issued';
        }
        // Set issue date if first time issuing
        if ($this->issue_date === null && $quantity > 0) {
            // Explicitly cast to string date
            $this->issue_date = now()->toDateString();
        }
        return $this->save();
    }
    // Return item quantity
    public function returnQuantity(int $quantity, string $condition = 'good'): bool
    {
        if (!$this->canReturn($quantity)) {
            return false;
        }
        $this->returned_quantity += $quantity;
        $this->condition_on_return = $condition;
        // Set return date if first time returning
        if ($this->return_date === null && $quantity > 0) {
            // Explicitly cast to string date
            $this->return_date = now()->toDateString();
        }
        return $this->save();
    }
    // Get the cost at time of request
    public function getCostAtTime(): float
    {
        return (float) $this->unit_cost_at_time;
    }
    // Get total cost of this request item
    public function getTotalCost(): float
    {
        return (float) $this->total_cost;
    }
    // Check if fully issued
    public function isFullyIssued(): bool
    {
        return $this->issuance_status === 'fully_issued';
    }
    // Check if partially issued
    public function isPartiallyIssued(): bool
    {
        return $this->issuance_status === 'partially_issued';
    }
    // Check if not issued
    public function isNotIssued(): bool
    {
        return $this->issuance_status === 'not_issued';
    }

}