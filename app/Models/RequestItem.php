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
        'issue_date'          => 'date',
        'due_date'            => 'date',
        'return_date'         => 'date',
        'unit_cost_at_time'   => 'decimal:2',
        'total_cost'          => 'decimal:2',
        'status'              => 'string',
        'issuance_status'     => 'string',
        'condition_on_return' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($requestItem) {
            // FIX: Wrap in try-catch so a missing unit_cost column or null
            // relationship never corrupts the save or mangles updated_at.
            try {
                $requestItem->calculateCosts();
            } catch (\Exception $e) {
                // Cost calculation is optional — don't block saving
            }

            // Auto-set issuance_status based on quantities
            if ($requestItem->approved_quantity !== null) {
                $issued = (int) $requestItem->issued_quantity;
                $approved = (int) $requestItem->approved_quantity;

                if ($issued === 0) {
                    $requestItem->issuance_status = 'not_issued';
                } elseif ($issued >= $approved) {
                    $requestItem->issuance_status = 'fully_issued';
                } else {
                    $requestItem->issuance_status = 'partially_issued';
                }
            }
        });
    }

    public function itemRequest()
    {
        return $this->belongsTo(ItemRequest::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function issuance()
    {
        return $this->belongsTo(Issuance::class);
    }

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

    public function calculateCosts()
    {
        // FIX: Guard against missing relationship or missing unit_cost column.
        // If item doesn't exist or has no unit_cost, skip silently.
        if (
            $this->item &&
            isset($this->item->unit_cost) &&
            $this->item->unit_cost !== null
        ) {
            $this->unit_cost_at_time = (string) $this->item->unit_cost;
            $this->total_cost        = (string) ($this->item->unit_cost * $this->quantity);
        } else {
            $this->unit_cost_at_time = $this->unit_cost_at_time ?? null;
            $this->total_cost        = $this->total_cost ?? null;
        }
    }

    public function isOverdue(): bool
    {
        return $this->due_date &&
               $this->due_date < now() &&
               $this->issuance_status === 'issued' &&
               $this->returned_quantity < $this->issued_quantity;
    }

    public function getRemainingToIssue(): int
    {
        if ($this->approved_quantity === null) {
            return 0;
        }
        return max(0, (int) $this->approved_quantity - (int) $this->issued_quantity);
    }

    public function getRemainingToReturn(): int
    {
        return max(0, (int) $this->issued_quantity - (int) $this->returned_quantity);
    }

    public function canIssue(int $quantity): bool
    {
        if ($this->approved_quantity === null) {
            return false;
        }
        return $quantity <= $this->getRemainingToIssue();
    }

    public function canReturn(int $quantity): bool
    {
        return $quantity <= $this->getRemainingToReturn();
    }

    public function issueQuantity(int $quantity): bool
    {
        if (!$this->canIssue($quantity)) {
            return false;
        }
        $this->issued_quantity += $quantity;

        if ($this->issued_quantity == 0) {
            $this->issuance_status = 'not_issued';
        } elseif ($this->issued_quantity >= $this->approved_quantity) {
            $this->issuance_status = 'fully_issued';
        } else {
            $this->issuance_status = 'partially_issued';
        }

        if ($this->issue_date === null && $quantity > 0) {
            $this->issue_date = now()->toDateString();
        }

        return $this->save();
    }

    public function returnQuantity(int $quantity, string $condition = 'good'): bool
    {
        if (!$this->canReturn($quantity)) {
            return false;
        }
        $this->returned_quantity   += $quantity;
        $this->condition_on_return  = $condition;

        if ($this->return_date === null && $quantity > 0) {
            $this->return_date = now()->toDateString();
        }

        return $this->save();
    }

    public function getCostAtTime(): float
    {
        return (float) ($this->unit_cost_at_time ?? 0);
    }

    public function getTotalCost(): float
    {
        return (float) ($this->total_cost ?? 0);
    }

    public function isFullyIssued(): bool
    {
        return $this->issuance_status === 'fully_issued';
    }

    public function isPartiallyIssued(): bool
    {
        return $this->issuance_status === 'partially_issued';
    }

    public function isNotIssued(): bool
    {
        return $this->issuance_status === 'not_issued';
    }
}