<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'title', 'description', 'type',
        'priority', 'status', 'created_by',
        'assigned_to', 'device_id', 'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
