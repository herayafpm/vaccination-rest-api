<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function vaccine()
    {
        return $this->hasOne(Vaccine::class,'id','vaccine_id');
    }
    public function spot()
    {
        return $this->hasOne(Spot::class,'id','spot_id');
    }

    public function vaccinator()
    {
        return $this->hasOne(Medical::class,'id','doctor_id');
    }
}
