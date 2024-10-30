<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShalawatListResource extends JsonResource
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
            'muhud' => $this->muhud,
            'position' => $this->position,
            'text' => $this->text,
            'isDiwan' => $this->is_diwan === 'Y' ? true : false,
            'isDiba' => $this->is_diba === 'Y' ? true : false,
            'translateId' => $this->translate_id,
            'transliteration' => $this->transliteration,
            'audios' => $this->audios,
        ];
    }
}
