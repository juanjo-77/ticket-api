<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    protected $fillable = [
        'name', 'type', 'serial_number',
        'status', 'assigned_to', 'assigned_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
