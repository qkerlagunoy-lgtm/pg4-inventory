<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Item extends Model
{
    use HasFactory;
    
    // Booted method
    protected static function boot()
    {
        parent::boot();
        // Calculate total value whenever quantity or unit_cost changes
        static::saving(function ($item) {
            if ($item->unit_cost !== null && $item->quantity !== null) {
                $item->total_value = $item->unit_cost * $item->quantity;
            } else {
                $item->total_value = null;
            }
            // Update last_restocked when quantity increases
            if ($item->isDirty('quantity') && $item->quantity > $item->getOriginal('quantity')) {
                $item->last_restocked = now();
            }
        });
    }
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'quantity',
        'minimum_quantity',
        'unit_of_measure',      
        'unit_cost',           
        'total_value',         
        'last_restocked',
        'expiration_date',
        'is_active',
    ];
     protected $casts = [
        'expiration_date' => 'date',
        'is_active' => 'boolean',
    ];
     // Get the category this item belongs to
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // Get all request items for this item
    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }
    // Check if item is low in stock
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->minimum_quantity;
    }
    // Check if item is expired or expiring soon
    public function isExpiringSoon($days = 30): bool
    {
        if (!$this->expiration_date) {
            return false;
        }
        
        return $this->expiration_date <= now()->addDays($days);
    }
    // Check if item is expired
    public function isExpired(): bool
    {
        if (!$this->expiration_date) {
            return false;
        }
        
        return $this->expiration_date < now();
    }
    // Check if item is available
    public function isAvailable(): bool
    {
        return $this->quantity > 0 && $this->is_active && !$this->isExpired();
    }
    // Decrease quantity (when item is used/issued)
    public function decreaseQuantity($amount): bool
    {
        if ($this->quantity < $amount) {
            return false;
        }   
        $this->quantity -= $amount;
        return $this->save();
    }
    // Increase quantity (when new stock arrives)
    public function increaseQuantity($amount): bool
    {
        $this->quantity += $amount;
        return $this->save();
    }
    // Scope a query to only include active items
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    // Scope a query to only include low stock items
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= minimum_quantity')
                    ->where('quantity', '>', 0);
    }
    // Scope a query to only include expiring soon items
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('expiration_date')
                    ->where('expiration_date', '<=', now()->addDays($days))
                    ->where('expiration_date', '>', now());
    }
    // Scope a query to only include expired items
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiration_date')
                    ->where('expiration_date', '<=', now());
    }
    // Scope a query to only include available items
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
                    ->where('quantity', '>', 0)
                    ->where(function($q) {
                        $q->whereNull('expiration_date')
                          ->orWhere('expiration_date', '>', now());
                    });
    }
    // Calculate total inventory value
    public static function getTotalInventoryValue(): float
    {
        return self::where('is_active', true)
            ->sum(DB::raw('COALESCE(total_value, 0)'));
    }
    // Get items that need reordering
    public static function needsReorder()
    {
        return self::where('is_active', true)
            ->whereRaw('quantity <= minimum_quantity')
            ->orderBy('quantity', 'asc')
            ->get();
    }
    // Update cost information when issuing items
    public function updateCostInfo($unitCost = null)
    {
        if ($unitCost !== null) {
            $this->unit_cost = $unitCost;
        }
        
        if ($this->unit_cost !== null && $this->quantity !== null) {
            $this->total_value = $this->unit_cost * $this->quantity;
            $this->save();
        }
        
        return $this;
    }
    // Restock item with cost information
    public function restock($quantity, $unitCost = null, $expirationDate = null): bool
    {
        $this->quantity += $quantity;
        $this->last_restocked = now();
        if ($unitCost !== null) {
            // Update unit cost (weighted average if there's existing stock)
            if ($this->quantity > $quantity && $this->unit_cost !== null) {
                $existingValue = ($this->quantity - $quantity) * $this->unit_cost;
                $newValue = $quantity * $unitCost;
                $this->unit_cost = ($existingValue + $newValue) / $this->quantity;
            } else {
                $this->unit_cost = $unitCost;
            }
        }
        if ($expirationDate !== null) {
            $this->expiration_date = $expirationDate;
        }
        // Calculate total value
        if ($this->unit_cost !== null) {
            $this->total_value = $this->unit_cost * $this->quantity;
        }
        return $this->save();
    }
    // Get inventory aging information
    public function getInventoryAge(): ?int
    {
        if (!$this->last_restocked) {
            return null;
        }
        
        return now()->diffInDays($this->last_restocked);
    }
    // Accessor for low stock warning  
    public function getLowStockWarningAttribute(): string
    {
        if ($this->quantity <= 0) {
            return 'Out of Stock';
        }   
        if ($this->isLowStock()) {
            $percentage = ($this->quantity / $this->minimum_quantity) * 100;
            return "Low Stock ({$this->quantity} left, {$percentage}%)";
        }
        return 'In Stock';
    }
    // Accessor for expiry status
    public function getExpiryStatusAttribute(): string
    {
        if (!$this->expiration_date) {
            return 'No Expiry';
        }   
        if ($this->isExpired()) {
            $days = now()->diffInDays($this->expiration_date);
            return "Expired {$days} days ago";
        }
        if ($this->isExpiringSoon()) {
            $days = now()->diffInDays($this->expiration_date);
            return "Expires in {$days} days";
        }
        return 'Good';
    }
    // Accessor for formatted unit cost
    public function getFormattedUnitCostAttribute(): string
    {
        if (!$this->unit_cost) {
            return 'N/A';
        }
        return '₱' . number_format($this->unit_cost, 2);
    }
    // Accessor for formatted total value
    public function getFormattedTotalValueAttribute(): string
    {
        if (!$this->total_value) {
            return 'N/A';
        }
        
        return '₱' . number_format($this->total_value, 2);
    }
}