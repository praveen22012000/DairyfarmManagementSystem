<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAllocation extends Model
{
    use HasFactory;

        protected $fillable=['order_id','product_id','stock_id','allocated_quantity','delivered_quantity','is_delivered'];

    	public function order()
        {
            return $this->belongsTo(RetailorOrder::class, 'order_id');
        }

}
