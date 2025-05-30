<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
        'total_amount',
    ];

    // Order.php
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->without('orders');
    }

    public function albums()
    {
        return $this->belongsToMany(Album::class)
            ->withPivot(['quantity', 'unit_price'])
            ->withTimestamps();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
}