<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengingat extends Model
{
    use HasFactory;
    Protected $guarded=[];

    public function pengingat(): BelongsTo
    {
        return $this->belongsTo(Pengingat::class);
    }
}
