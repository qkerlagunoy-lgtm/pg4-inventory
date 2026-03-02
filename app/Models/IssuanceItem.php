<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IssuanceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'issuance_items';

    protected $fillable = [
        'issuance_id',
        'item_request_id',
        'request_item_id',
        'item_id',
        'quantity_issued',
        'quantity_returned',
        'issue_date',
        'due_date',
        'return_date',
        'condition_on_return',
        'unit_cost_at_time',
        'total_cost',
        'status', // issued, returned, partially_returned, lost, damaged
        'notes',
        'processed_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'quantity_issued' => 'integer',
        'quantity_returned' => 'integer',
        'unit_cost_at_time' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'status' => 'string',
        'condition_on_return' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function issuance()
    {
        return $this->belongsTo(Issuance::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemRequest()
    {
        return $this->belongsTo(ItemRequest::class);
    }

    public function requestItem()
    {
        return $this->belongsTo(RequestItem::class, 'request_item_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Status check methods
    public function isIssued(): bool
    {
        return $this->status === 'issued';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    public function isPartiallyReturned(): bool
    {
        return $this->status === 'partially_returned';
    }

    public function isLost(): bool
    {
        return $this->status === 'lost';
    }

    public function isDamaged(): bool
    {
        return $this->status === 'damaged';
    }

    // Overdue check
    public function isOverdue(): bool
    {
        return $this->due_date && 
               $this->due_date < now() && 
               $this->status === 'issued' &&
               $this->getRemainingQuantityAttribute() > 0;
    }

    // Quantity helpers
    public function getRemainingQuantityAttribute(): int
    {
        return max(0, $this->quantity_issued - $this->quantity_returned);
    }

    public function getReturnPercentageAttribute(): float
    {
        if ($this->quantity_issued === 0) return 0;
        return round(($this->quantity_returned / $this->quantity_issued) * 100, 2);
    }

    // Cost helpers
    public function getTotalCostAttribute(): float
    {
        return (float) ($this->total_cost ?? 0);
    }

    public function getUnitCostAttribute(): float
    {
        return (float) ($this->unit_cost_at_time ?? 0);
    }

    // Action methods
    public function markAsReturned(int $quantity, string $condition = 'good', ?int $processedBy = null): bool
    {
        if ($quantity > $this->getRemainingQuantityAttribute()) {
            return false;
        }

        $this->quantity_returned += $quantity;
        $this->condition_on_return = $condition;
        $this->return_date = now()->toDateString();
        
        if ($processedBy) {
            $this->processed_by = $processedBy;
        }

        // Update status based on return quantity
        if ($this->quantity_returned >= $this->quantity_issued) {
            $this->status = 'returned';
        } else {
            $this->status = 'partially_returned';
        }

        return $this->save();
    }

    public function markAsLost(int $quantity, ?int $processedBy = null): bool
    {
        if ($quantity > $this->getRemainingQuantityAttribute()) {
            return false;
        }

        $this->quantity_returned += $quantity;
        $this->condition_on_return = 'lost';
        $this->return_date = now()->toDateString();
        $this->status = 'lost';
        
        if ($processedBy) {
            $this->processed_by = $processedBy;
        }

        return $this->save();
    }

    // Scopes
    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    public function scopeReturned($query)
    {
        return $query->whereIn('status', ['returned', 'partially_returned']);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('due_date')
                    ->where('due_date', '<', now())
                    ->where('status', 'issued')
                    ->whereRaw('quantity_returned < quantity_issued');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    public function scopeDamaged($query)
    {
        return $query->where('condition_on_return', 'damaged');
    }

    public function scopeDueBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('due_date', [$startDate, $endDate]);
    }

    public function scopeIssuedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('issue_date', [$startDate, $endDate]);
    }

    public function scopeReturnedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('return_date', [$startDate, $endDate]);
    }

    // Boot method for auto-calculations
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($issuanceItem) {
            // Auto-calculate total cost if unit cost exists
            if ($issuanceItem->unit_cost_at_time && $issuanceItem->quantity_issued) {
                $issuanceItem->total_cost = $issuanceItem->unit_cost_at_time * $issuanceItem->quantity_issued;
            }
        });

        static::created(function ($issuanceItem) {
            // Update the related request item's status if needed
            // This can be handled by the controller or observer pattern
        });
    }
}