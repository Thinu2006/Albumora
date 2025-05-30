<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'artist', 
        'release_year', 
        'price', 
        'stock', 
        'cover_image', 
        'created_by'
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}