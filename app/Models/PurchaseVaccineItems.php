<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseVaccineItems extends Model
{
    use HasFactory;

    protected $fillable=['purchase_id','vaccine_id','unit_price','purchase_quantity','initial_quantity','stock_quantity','manufacture_date','expire_date'];

    public function purchase_vaccine()
    {
        return $this->belongsTo(PurchaseVaccine::class,'purchase_id');
    }

    
    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class,'vaccine_id');
    }
}
