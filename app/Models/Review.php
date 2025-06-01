<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Review extends Eloquent
{
    protected $connection = 'mongodb';
    protected $fillable = ['text', 'customer_id'];

    // Use an accessor instead of relationship
    public function getCustomerAttribute()
    {
        if (!isset($this->attributes['customer'])) {
            $this->attributes['customer'] = \App\Models\User::find($this->customer_id);
        }
        return $this->attributes['customer'];
    }
}