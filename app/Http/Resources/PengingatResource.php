<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengingatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'nama_pengingat' => $this->nama_pengingat,
            'keterangan_pengingat' => $this->keterangan_pengingat,
            'mulai_pengingat' => $this->mulai_pengingat,
            'selesai_pengingat' => $this->selesai_pengingat,
            'list_user' => $this->users,
            // 'keterangan_pengingat' => $this->email,

        ];
    }
}
