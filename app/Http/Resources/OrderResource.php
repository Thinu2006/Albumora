<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'customer' => new UserResource($this->whenLoaded('customer')),
            'albums' => AlbumResource::collection($this->whenLoaded('albums')),
            'items' => $this->whenLoaded('albums', function() {
                return $this->albums->map(function($album) {
                    return [
                        'id' => $album->id,
                        'quantity' => $album->pivot->quantity,
                        'unit_price' => $album->pivot->unit_price,
                        'album' => [  // Nest album info in an 'album' object for better structure
                            'id' => $album->id,
                            'title' => $album->title,
                            'cover_image' => $album->cover_image ? 
                                asset('storage/'.$album->cover_image) : 
                                null,
                            // Add any other album fields you need
                        ]
                    ];
                });
            }),
            'payment' => new PaymentResource($this->whenLoaded('payment')),
            'shipment' => new ShipmentResource($this->whenLoaded('shipment')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}