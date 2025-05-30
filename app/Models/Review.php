<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'customer_id'];

    // Define the inverse of the relationship in User model
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}