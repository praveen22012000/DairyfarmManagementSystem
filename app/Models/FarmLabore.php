<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmLabore extends Model
{
    use HasFactory;

    protected $fillable=['user_id','birth_date','hire_date','status'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function retailor_order()
    {
        return $this->hasMany(RetailorOrder::class,'delivery_person_id');
    }

    public function task_assignment()
    {
        return $this->hasMany(TaskAssignment::class,'assigned_to');
    }
}
