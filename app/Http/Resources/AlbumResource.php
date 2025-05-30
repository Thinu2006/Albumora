<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage; // Add this import

class AlbumResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'artist' => $this->artist,
            'release_year' => $this->release_year,
            'price' => (float)$this->price, 
            'stock' => $this->stock,
            'cover_image' => $this->cover_image ? 
                asset('storage/'.$this->cover_image) : 
                null,
            'genres' => $this->genres->map(function($genre) {
                return [
                    'id' => $genre->id,
                    'name' => $genre->name
                ];
            }),
            'creator' => $this->creator ? [
                'id' => $this->creator->id,
                'name' => $this->creator->name
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}