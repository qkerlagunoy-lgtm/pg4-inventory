<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    protected $fillable = [
        'user_id',
        'purpose',
        'status',
        'priority',
        'request_date',
        'required_date',
        'remarks',
        'notes',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'cancelled_by',
        'cancelled_at',
    ]; 
    protected $casts = [
        'request_date' => 'datetime',
        'required_date' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];
    /**
     * Get the user who made the request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the items requested
     */
    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }
    /**
     * Get the admin who approved the request
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    /**
     * Get the admin who rejected the request
     */
    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
    /**
     * Get the user who cancelled the request
     */
    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
    /**
     * Check if request is urgent
     */
    public function isUrgent(): bool
    {
        return $this->status === 'urgent';
    }
    /**
     * Check if request is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}