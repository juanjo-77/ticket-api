<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
    'title', 'description', 'type',
    'priority', 'status', 'created_by',
    'assigned_to', 'device_id', 'resolved_at'
];

}
