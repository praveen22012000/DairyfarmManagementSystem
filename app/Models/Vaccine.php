<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable=['vaccine_name','manufacturer','unit_type','unit_price'];

    

      public function purchase_vaccine_items()
    {
        return $this->hasMany(PurchaseVaccineItems::class,'vaccine_id');
    }

    public function vaccine_consume_items()
    {
      return $this->hasMany(VaccineConsumeItems::class,'vaccination_id');
    }
}
