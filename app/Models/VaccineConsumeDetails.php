<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineConsumeDetails extends Model
{
    use HasFactory;

    protected $fillable=['vaccination_date','appointment_id'];

    public function vaccine_consume_items()
    {
        return $this->hasMany(VaccineConsumeItems::class,'vaccine_consume_detail_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class,'appointment_id');
    }
}
