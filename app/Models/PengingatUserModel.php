<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengingatUserModel extends Model
{
    use HasFactory;
    protected $table = 'pengingat_user';
    protected $guarded =[];
    public $timestamps = false;
}
