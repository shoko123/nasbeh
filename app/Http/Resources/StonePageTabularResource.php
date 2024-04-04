<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StonePageTabularResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'square' => $this->square,
            'context' => $this->context,
            'description' => $this->description,
            'conservation_notes' => $this->conservation_notes,
        ];
    }
}
