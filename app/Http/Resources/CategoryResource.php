<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'image' => $this->image === 'images/default.jpg' ? asset($this->image): asset('storage/' . $this->image),
            'active' => $this->active ?? false,
        ];
    }
}
