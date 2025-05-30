<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
}
