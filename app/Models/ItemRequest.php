<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    protected $fillable = [
        'user_id',
        'purpose',
        'status',
        'issuance_status',
        'priority',
        'request_date',
        'required_date',
        'scheduled_issue_date',
        'actual_issue_date',
        'issued_by',
        'expected_return_date',
        'return_status',
        'tracking_number',
        'delivery_method',
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
        'scheduled_issue_date' => 'date',
        'actual_issue_date' => 'date',
        'expected_return_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'issuance_status' => 'string',
        'return_status' => 'string',
    ];
    // Get the user who made the request
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Get the items requested
    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }
    // Get the admin who approved the request
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    // Get the admin who rejected the request
    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
    // Get the user who cancelled the request
    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
    // Check if request is pending
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    // Check if request is approved
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    // Check if request is rejected
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
    // Check if request is urgent
    public function isUrgent(): bool
    {
        return $this->status === 'urgent';
    }
    // Check if request is cancelled
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
    public function issuance()
    {
        return $this->hasOne(Issuance::class);
    }
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
    public function updateIssuanceStatus()
    {
        $totalItems = $this->requestItems->count();
        $issuedItems = $this->requestItems->where('issuance_status', 'fully_issued')->count();
        $partiallyIssued = $this->requestItems->where('issuance_status', 'partially_issued')->count();
        
        if ($issuedItems == 0 && $partiallyIssued == 0) {
            $this->issuance_status = 'not_issued';
        } elseif ($issuedItems == $totalItems) {
            $this->issuance_status = 'fully_issued';
        } else {
            $this->issuance_status = 'partially_issued';
        }
        
        $this->save();
    }
    public function updateReturnStatus()
    {
        $totalIssued = $this->requestItems->sum('issued_quantity');
        $totalReturned = $this->requestItems->sum('returned_quantity');
        
        if ($totalReturned == 0) {
            $this->return_status = 'not_returned';
        } elseif ($totalReturned == $totalIssued) {
            $this->return_status = 'fully_returned';
        } else {
            $this->return_status = 'partially_returned';
        }
        
        $this->save();
    }

    public function getTotalRequestedValue()
    {
        return $this->requestItems->sum('total_cost');
    }
    // Add this accessor
    public function getIssuanceStatusLabelAttribute(): string
    {
        $labels = [
            'not_issued' => 'Not Issued',
            'partially_issued' => 'Partially Issued',
            'fully_issued' => 'Fully Issued',
        ];

        return $labels[$this->issuance_status] ?? $this->issuance_status;
    }
}