<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'item_id', 'quantity', 'notes'];

    public function item()
    {
        return $this->belongsTo(Item::class); // ✅ fixed
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}