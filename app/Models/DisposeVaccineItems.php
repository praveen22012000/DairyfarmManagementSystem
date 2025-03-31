<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposeVaccineItems extends Model
{
    use HasFactory;

    protected $fillable=['purchase_vaccine_item_id','dispose_vaccine_detail_id','dispose_quantity','reason_for_dispose'];

    public function dispose_vaccine_details()
    {
        return $this->belongsTo(DisposeVaccineDetails::class,'dispose_vaccine_detail_id');
    }

    public function purchase_vaccine_items()
    {
        return $this->belongsTo(PurchaseVaccineItems::class,'purchase_vaccine_item_id');
    }
}
