<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Retailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'business_type',
        'tax_id',
        'retailer_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'retailer_id');
    }

    


}
