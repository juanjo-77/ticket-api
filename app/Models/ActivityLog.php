<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
    'user_id', 'action', 'entity_type',
    'metadata' => 'array',
    'entity_id', 'metadata', 'ip_address'
];
}
