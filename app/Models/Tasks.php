<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable=['title','description'];

    public function task_assignment()
    {
        return $this->hasMany(TaskAssignment::class,'task_id');
    }
}
