<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personal_akses_token extends Model
{
    use HasFactory;
    protected $table = 'personal_access_tokens';
    protected $guarded=[];
    public $timestamps = false;
}
