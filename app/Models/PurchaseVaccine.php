<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseVaccine extends Model
{
    use HasFactory;

    protected $fillable=['supplier_id','purchase_date','pay_mode','user_id'];

    public function purchase_vaccine_payment()
    {
        return $this->hasOne(PurchaseVaccinePayments::class,'purchase_id');
    }

    public function purchase_vaccine_items()
    {
        return $this->hasMany(PurchaseVaccineItems::class,'purchase_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
}
 