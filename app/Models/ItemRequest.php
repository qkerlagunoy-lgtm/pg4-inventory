<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'purpose',
        'status',
        'remarks',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'request_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}