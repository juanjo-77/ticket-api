<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
    'name', 'type', 'serial_number',
    'status', 'assigned_to', 'assigned_at'
];

protected $casts = [
    'assigned_at' => 'datetime',
];
}
