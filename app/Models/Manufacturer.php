<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable=['date','time','enter_by'];

    public function manufacturer_product()
    {
        $this->hasMany(ManufacturerProduct::class,'manufacturer_id');
    }
}
