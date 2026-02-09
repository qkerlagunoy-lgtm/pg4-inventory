<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];
    // Get the user who owns the notification
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Mark notification as read
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
    // Scope a query to only include unread notifications
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    // Scope a query to only include read notifications
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
    // Get notification type label
    public function getTypeLabelAttribute(): string
    {
        $labels = [
            'request_approved' => 'Request Approved',
            'request_rejected' => 'Request Rejected',
            'items_issued' => 'Items Issued',
            'item_returned' => 'Item Returned',
            'request_cancelled' => 'Request Cancelled',
            'low_stock' => 'Low Stock Alert',
            'expiring_soon' => 'Expiry Alert',
        ];

        return $labels[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }
}
