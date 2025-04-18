<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineConsumeItems extends Model
{
    use HasFactory;

    protected $fillable=['animal_id','vaccine_id','vaccination_item_id','vaccine_consume_detail_id','consumed_quantity'];

    public function vaccine_consume_detail()
    {
        return $this->belongsTo(VaccineConsumeDetails::class,'vaccine_consume_detail_id');
    }

    public function animal()
    {
        return $this->belongsTo(AnimalDetail::class,'animal_id');
    }

    public function vaccinations()
    {
        return $this->belongsTo(Vaccine::class,'vaccination_id');
    }

    public function purchase_vaccine_items()
    {
        return $this->belongsTo(PurchaseVaccineItems::class,'vaccination_item_id');
    }
}
