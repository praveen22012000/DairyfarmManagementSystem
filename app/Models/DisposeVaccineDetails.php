<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposeVaccineDetails extends Model
{
    use HasFactory;

    protected $fillable=['dispose_date','dispose_time','user_id'];

    public function dispose_vaccine_items()
    {
        return $this->hasMany(DisposeVaccineItems::class,'dispose_vaccine_detail_id');
    }
}
