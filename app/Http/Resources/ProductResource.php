<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image === 'images/default.jpg' ? asset($this->image): asset('storage/' . $this->image),
            'sold' => $this->sold,
            'price' => $this->price,
            'discount_price' => $this->discount_price ?? $this->price,
            'code' => $this->code,
            'weight' => $this->weight,
            'quantity' => $this->quantity,
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
