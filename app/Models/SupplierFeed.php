<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierFeed extends Model
{
    use HasFactory;

    protected $fillable=['feed_id','supplier_id'];
}
