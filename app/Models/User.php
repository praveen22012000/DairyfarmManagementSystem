<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'lastname',
        'address',
        'phone_number',
        'role_id',
        'nic',
        'gender',
        'birthdate'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     public function sales_manager()
    {
         return $this->hasMany(SalesManager::class,'sales_manager_id');
    }

    public function general_manager()
    {
         return $this->hasMany(GeneralManager::class,'general_manager_id');
    }

    public function task_assignment()
    {
        return $this->hasMany(TaskAssignment::class,'assigned_by');
    }

    public function retailor_order()
    {
        return $this->hasMany(RetailorOrder::class,'retailor_id');
    }

    public function purchase_feed_payments()
    {
        return $this->hasMany(PurchaseFeedPayment::class,'payment_receiver');
    }


    public function purchase_vaccine_payments()
    {
        return $this->hasMany(PurchaseVaccinePayments::class,'payment_receiver');
    }


    public function appointment()
    {
        return $this->hasMany(Appointment::class,'veterinarian_id');
    }


    public function dispose_milk_product()
    {
        return $this->hasMany(DisposeMilk::class,'user_id');
    }


    public function manufacture_product()
    {
        return $this->hasMany(ManufacturerProduct::class,'user_id');
    }


    public function farm_labore()
    {
        return $this->hasOne(FarmLabore::class,'farm_labore_id');
    }


    public function veterinarian()
    {
        return $this->belongsTo(Veterinarian::class,'veterinarian_id');
    }

    public function retailer()
    {
        return $this->belongsto(Retailer::class,'retailer_id');
    }

    public function role()
    {
       return $this->belongsTo(Role::class);
    }

    public function BreedingEvents()
    {
        return $this->hasMany(BreedingEvents::class,'veterinarian_id');
    }

    //this function establish a relationship to the AnimalCalvings Model
    public function AnimalCalvings()
    {
        return $this->hasMany(AnimalCalvings::class,'veterinarian_id');
    }

//this function establish the relationship between the User and Pregnancy model
    public function pregnancies()
    {
        return $this->hasMany(Pregnancies::class,'veterinarian_id');
    }

//this function is used to establish the relationship with ProductionMilk model
    public function ProductionMilk()
    {
        return $this->hasMany(ProductionMilk::class,'user_id');
    }

    public function DisposeMilk()
    {
        return $this->hasMany(DisposeMilk::class,'user_id');
    }

    public function feed_consume_detail()
    {
        return $this->hasMany(FeedConsumeDetail::class,'user_id');
    }
}
