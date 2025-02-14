<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionMilk extends Model
{
    use HasFactory;

    protected $fillable=[
        'animal_id',
        'user_id',
        'production_date',
        'Quantity_Liters',
        'shift',
        'fat_percentage',
        'protein_percentage',
        'lactose_percentage',
        'somatic_cell_count',
        'stock_quantity'
    ];

    public function AnimalDetail()
    {
        return $this->belongsTo(AnimalDetail::class,'animal_id');
    }

    public function ProductionSupplyDetails()
    {
        return $this->hasMany(ProductionSupplyDetails::class,'production_milk_id');
    }

    //this function is used to establish the relationship between User model
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function Dipose_Milk()
    {
        return $this->hasMany(DiposeMilk::class,'production_milk_id');
    }
}
