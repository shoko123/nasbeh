<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocusPageTabularResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'oc_label' => $this->oc_label,
            'basic_typology' => $this->category,
            'number' => $this->a,
            'sub_number' => $this->b,
            'square' => $this->square,
            'published_date' => $this->published_date,
            'updated_date' => $this->updated_date,
        ];
    }
}
