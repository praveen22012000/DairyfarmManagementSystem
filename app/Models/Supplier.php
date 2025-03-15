<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable=['name','phone_no','email','address'];

    public function feeds()
    {
        return $this->belongsToMany(Feed::class, 'supplier_feeds');
    }

    // Relationship with vaccines
    public function vaccines()
    {
        return $this->belongsToMany(Vaccine::class, 'supplier_vaccines');
    }

}
