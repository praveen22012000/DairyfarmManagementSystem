<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySalaryAssignment extends Model
{
    use HasFactory;

    protected $fillable=['user_id','amount_paid','salary_month','paid_at'];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
}
