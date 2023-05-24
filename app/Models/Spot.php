<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $hidden = ['regional_id'];

    public function regional()
    {
        return $this->hasOne(Regional::class,'id','regional_id');
    }
}
