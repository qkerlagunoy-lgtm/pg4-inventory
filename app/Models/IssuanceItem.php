<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IssuanceItem extends Model
{
     use HasFactory, SoftDeletes;

    protected $fillable = [
        'issuance_id',
        'item_id',
        'quantity_issued',
        'quantity_returned',
        'due_date',
        'status',
        'notes',
    ];
    protected $casts = [
        'due_date' => 'date',
    ];
    // Get the parent issuance
    public function issuance()
    {
        return $this->belongsTo(Issuance::class);
    }
    // Get the item details
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    // Check if item is overdue
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date < now() && $this->status === 'issued';
    }
    // Check if item is returned
    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }
    // Get remaining quantity to return
    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity_issued - $this->quantity_returned;
    }
    //Scope a query to only include overdue items
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('due_date')
                    ->where('due_date', '<', now())
                    ->where('status', 'issued');
    }
    // Scope a query to only include returned items  
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }
}
