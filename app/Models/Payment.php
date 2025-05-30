<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'card_type',
        'cardholder_name',
        'last_four',
        'amount',
        'payment_status',
        'transaction_id',
        'payment_date',
    ];
     protected $casts = [
        'payment_date' => 'datetime'
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}