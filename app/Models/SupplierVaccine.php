<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierVaccine extends Model
{
    use HasFactory;

    protected $fillable=['vaccine_id','supplier_id'];
}
