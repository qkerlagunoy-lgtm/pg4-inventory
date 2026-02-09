<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Issuance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_request_id',
        'issued_by',
        'issued_at',
        'status',
        'remarks',
    ];
    protected $casts = [
        'issued_at' => 'datetime',
    ];
    // Get the item request for this issuance
    public function itemRequest()
    {
        return $this->belongsTo(ItemRequest::class);
    }
    // Get the admin who issued the items
    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
    // Get all items in this issuance
    public function issuanceItems()
    {
        return $this->hasMany(IssuanceItem::class);
    }
    // Get total quantity issued
    public function getTotalIssuedAttribute(): int
    {
        return $this->issuanceItems->sum('quantity_issued');
    }
    // Get total quantity returned
    public function getTotalReturnedAttribute(): int
    {
        return $this->issuanceItems->sum('quantity_returned');
    }
    // Check if issuance is completed
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
    // Check if issuance is pending
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    // Get overdue items
    public function overdueItems()
    {
        return $this->issuanceItems()
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->where('status', 'issued');
    }
    //Get issuance code (formatted ID)
    public function getIssuanceCodeAttribute(): string
    {
        return 'ISS-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
     // Helper methods
    public function totalItemsIssued()
    {
        return $this->issuanceItems->sum('quantity_issued');
    }
    
    public function totalItemsReturned()
    {
        return $this->issuanceItems->sum('quantity_returned');
    }
    
    public function pendingReturnsCount()
    {
        return $this->issuanceItems->where('status', 'issued')->count();
    }
    
    public function canReturnItems()
    {
        return $this->issuanceItems->where('status', 'issued')->count() > 0;
    }
}
