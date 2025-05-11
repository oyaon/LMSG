<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'name' => $this->name,
            'author' => [
                'id' => $this->author_id,
                'name' => $this->author->name ?? $this->author,
            ],
            'category' => $this->category,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'cover_image' => $this->cover_image ? asset('storage/covers/' . $this->cover_image) : null,
            'pdf' => $this->pdf ? asset('storage/pdfs/' . $this->pdf) : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}