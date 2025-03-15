<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable=['vaccine_name','manufacturer','unit_type','unit_price'];

      // Relationship with suppliers
      public function suppliers()
      {
          return $this->belongsToMany(Supplier::class, 'supplier_vaccines');
      }
}
