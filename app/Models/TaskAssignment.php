<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $fillable=['task_id','assigned_by','assigned_to','due_date','assigned_date','status'];

    public function task()
    {
        return $this->belongsTo(Tasks::class,'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'assigned_by');
    }

    public function farm_labore()
    {
        return $this->belongsTo(FarmLabore::class,'assigned_to');
    }
}
