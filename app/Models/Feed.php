<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;

    
    protected $fillable=['feed_name','manufacturer','unit_type','unit_price'];

    
    // Relationship with suppliers
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_feeds');
    }
}
