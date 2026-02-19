<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        // Name fields
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        // Account info
        'username',
        'email',
        'password',
        'email_verified_at', // ADD THIS LINE
        // Profile
        'sex',
        'unit',
        'category_id',
        'type',
        'is_active',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // ==================== MUTATORS ====================
    
    // Automatically set the type based on email when creating/updating
    public function setTypeAttribute($value)
    {
        // If type is explicitly provided, use it
        if (!empty($value)) {
            $this->attributes['type'] = $value;
            return;
        }
        // List of emails that should be auto-set as admin
        $adminEmails = [
            'superadmin@gmail.com',
        ];
        // Auto-set type based on email
        if (in_array($this->email, $adminEmails)) {
            $this->attributes['type'] = 'admin';
        } else {
            $this->attributes['type'] = 'user'; // Default for all other emails
        }
    }
    // Automatically lowercase email and set type
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
        
        // If type hasn't been set yet, auto-set it based on email
        if (!isset($this->attributes['type'])) {
            $adminEmails = [
                'superadmin@gmail.com',
                'admin@afppgmc.com',
                'pg4admin@afppgmc.com',
            ];
            
            if (in_array($value, $adminEmails)) {
                $this->attributes['type'] = 'admin';
            } else {
                $this->attributes['type'] = 'user';
            }
        }
    }
    // Automatically generate username from email if not provided
    public function setUsernameAttribute($value)
    {
        if (empty($value) && isset($this->attributes['email'])) {
            // Generate username from email (part before @)
            $username = strtok($this->attributes['email'], '@');
            $this->attributes['username'] = $username;
        } else {
            $this->attributes['username'] = $value;
        }
    }
    // Automatically capitalize first names
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords(strtolower($value));
    }
    // Automatically capitalize last names
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords(strtolower($value));
    }
    // Automatically capitalize middle names (if provided)
    public function setMiddleNameAttribute($value)
    {
        if ($value) {
            $this->attributes['middle_name'] = ucwords(strtolower($value));
        } else {
            $this->attributes['middle_name'] = $value;
        }
    }
    // Automatically uppercase unit codes
    public function setUnitAttribute($value)
    {
        $this->attributes['unit'] = strtoupper($value);
    }
    // Automatically lowercase sex
    public function setSexAttribute($value)
    {
        $this->attributes['sex'] = strtolower($value);
    }
    // ==================== ACCESSORS ====================
    public function getFullNameAttribute(): string
    {
        return trim(
            "{$this->first_name} {$this->middle_name} {$this->last_name} {$this->suffix}"
        );
    }
     public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function itemRequests()
    {
        return $this->hasMany(ItemRequest::class);
    }
    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }
    public function pendingRequests()
    {
        return $this->itemRequests()->where('status', 'pending');
    }
    public function approvedRequests()
    {
        return $this->itemRequests()->where('status', 'approved');
    }
    public function rejectedRequests()
    {
        return $this->itemRequests()->where('status', 'rejected');
    }
     public function urgentRequests()
    {
        return $this->itemRequests()->where('status', 'urgent');
    }
    public function cancelledRequests()
    {
        return $this->itemRequests()->where('status', 'cancelled');
    }
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }
    public function isUser(): bool
    {
        return $this->type === 'user';
    }
    public function isSuperAdmin(): bool
    {
        return $this->type === 'admin' && $this->email === 'superadmin@gmail.com';
    }
    public function scopeAdmins($query)
    {
        return $query->where('type', 'admin');
    }
    public function scopeUsers($query)
    {
        return $query->where('type', 'user');
    }
    public function scopeFromUnit($query, $unit)
    {
        return $query->where('unit', $unit);
    }
    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class);
    }
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }
    public function getFormattedUnitAttribute(): string
    {
        $unitNames = [
            'BDCU' => 'Budget and Disbursement Control Unit',
            'CUI' => 'Command Unit Interface',
            'COMMAND' => 'Command Center',
            'ISU' => 'Information Systems Unit',
            'LSO' => 'Logistics Support Office',
            'PAU' => 'Planning and Assessment Unit',
            'PG1' => 'Program Group 1',
            'PG3' => 'Program Group 3',
            'PG4' => 'Program Group 4',
            'PG10' => 'Program Group 10',
            'PPBU' => 'Policy and Planning Bureau Unit',
        ];

        return $unitNames[$this->unit] ?? $this->unit;
    }
    public function getInitialsAttribute(): string
    {
        $initials = strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
        return $initials;
    }
    public function getProfileCompletionAttribute(): int
    {
        $fields = ['first_name', 'last_name', 'email', 'username', 'sex', 'unit', 'type'];
        $filled = 0;
        
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $filled++;
            }
        }
        
        return (int) (($filled / count($fields)) * 100);
    }
    public function canRequestItems(): bool
    {
        // Add your business logic here
        // Example: Only users with specific categories can request
        // return in_array($this->category_id, [1, 2, 3]);
        
        return $this->isUser(); // Default: All regular users can request
    }
    public function canApproveRequests(): bool
    {
        return $this->isAdmin();
    }
    public function canManageInventory(): bool
    {
        return $this->isAdmin();
    }
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }
    public function canManageCategories(): bool
    {
        return $this->isAdmin();
    }public function recentActivity()
    {
        return $this->itemRequests()
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(10);
    }
    public function getTotalRequestedItemsAttribute(): int
    {
        return $this->itemRequests()->count();
    }
    public function getTotalApprovedRequestsAttribute(): int
    {
        return $this->approvedRequests()->count();
    }
    public function getTotalPendingRequestsAttribute(): int
    {
        return $this->pendingRequests()->count();
    }
    public function getDashboardStatsAttribute(): array
    {
        return [
            'total_requests' => $this->total_requested_items,
            'approved_requests' => $this->total_approved_requests,
            'pending_requests' => $this->total_pending_requests,
            'rejected_requests' => $this->rejectedRequests()->count(),
            'urgent_requests' => $this->urgentRequests()->count(),
            'cancelled_requests' => $this->cancelledRequests()->count(),
        ];
    }
    public function issuances()
    {
        return $this->hasMany(Issuance::class, 'issued_by');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }
    // Add this method
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->unreadNotifications()->count();
    }
    // User belongs to a Unit
public function unit()
{
    return $this->belongsTo(Unit::class);
    
}

public function requests()
{
    return $this->hasMany(Request::class);
}


}