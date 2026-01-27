<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'seat',
        'member_ID',
        'member_amount',
        'order',
        'order_amount',
        'total',
        'paid',
        'online',
        'debt',
        'created_date',
        'modified_date',
    ];

    protected $casts = [
        'member_amount' => 'decimal:2',
        'order_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'debt' => 'decimal:2',
        'paid' => 'boolean',
        'online' => 'boolean',
        'created_date' => 'datetime',
        'modified_date' => 'datetime',
    ];
}
