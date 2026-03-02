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
        'status', // pending, completed, cancelled
        'remarks',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    // Relationships
    public function itemRequest()
    {
        return $this->belongsTo(ItemRequest::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function issuanceItems()
    {
        return $this->hasMany(IssuanceItem::class);
    }

    // Helper methods
    public function getTotalIssuedAttribute(): int
    {
        return $this->issuanceItems->sum('quantity_issued');
    }

    public function getTotalReturnedAttribute(): int
    {
        return $this->issuanceItems->sum('quantity_returned');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function getIssuanceCodeAttribute(): string
    {
        return 'ISS-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
