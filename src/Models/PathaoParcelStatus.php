<?php

namespace Kejubayer\PathaoIntegration\Models;

use Illuminate\Database\Eloquent\Model;

class PathaoParcelStatus extends Model
{
    protected $fillable = [
        'consignment_id',
        'merchant_order_id',
        'store_id',
        'event',
        'delivery_fee',
        'pathao_updated_at',
        'pathao_timestamp',
        'payload',
        'received_at',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'payload' => 'array',
        'pathao_updated_at' => 'datetime',
        'pathao_timestamp' => 'datetime',
        'received_at' => 'datetime',
    ];
}
