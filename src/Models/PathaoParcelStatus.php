<?php

namespace Kejubayer\PathaoIntegration\Models;

use Illuminate\Database\Eloquent\Model;

class PathaoParcelStatus extends Model
{
    protected $fillable = [
        'consignment_id',
        'merchant_order_id',
        'status',
        'payload',
        'received_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'received_at' => 'datetime',
    ];
}
