<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable=['veterinarian_id','appointment_date','appointment_time','notes'];

    public function user()
    {
        return $this->belongsTo(User::class,'veterinarian_id');
    }

    public function vaccine_consume_details()
    {
        return $this->belongsTo(VaccineConsumeDetails::class,'appointment_id');
    }
}
