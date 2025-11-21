<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "created_by" => $this->createdBy->name,
            "banner" => asset('storage/' . $this->banner),
            "title" => $this->title,
            "description" => $this->description,
            "created_at" => $this->created_at->format('M d Y')
        ];
    }
}
