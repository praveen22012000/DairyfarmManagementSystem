<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedConsumeDetails extends Model
{
    use HasFactory;

    protected $fillable=['animal_id','date','time','user_id'];

    public function feed_consume_item()
    {
        return $this->hasMany(FeedConsumeItems::class,'feed_consume_detail_id');
    }

    public function animal_details()
    {
        return $this->belongsTo(AnimalDetail::class,'animal_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
