<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class IpAddressRange extends Model
{
    protected $fillable = [
        'name', 'range_start', 'range_end', 'subnet_mask',
        'gateway', 'description', 'is_active',
    ];
 
    protected $casts = ['is_active' => 'boolean'];
}