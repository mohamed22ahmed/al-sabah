<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image === 'images/default.jpg' ? asset($this->image) : asset('storage/' . $this->image),
            'show' => (bool) $this->show,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
