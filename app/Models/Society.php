<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function regional()
    {
        return $this->hasOne(Regional::class,'id','regional_id');
    }
}
