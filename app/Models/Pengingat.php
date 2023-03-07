<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Pengingat extends Model
{
    use HasFactory;
    Protected $guarded=[];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
