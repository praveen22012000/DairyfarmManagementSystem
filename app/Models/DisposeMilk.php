<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposeMilk extends Model
{
    use HasFactory;

    protected $fillable=[
        'production_milk_id',
        'user_id',
           'dispose_quantity',
        'date',
        'reason_for_dispose',
     
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function production_milk()
    {
        return $this->belongsto(ProductionMilk::class,'production_milk_id');
    }
}
