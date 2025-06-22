<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SalesManager extends Model
{
    use HasFactory;

    protected $fillable=['sales_manager_id','sales_manager_qualification','sales_manager_hire_date'];

     public function user()
    {
        return $this->belongsTo(User::class,'sales_manager_id');
    }

    //this function is used to get the experience from the hire date
    public function getExperienceAttribute()
    {
        $hireDate = Carbon::parse($this->sales_manager_hire_date);
        $now = Carbon::now();

        // Calculate the total difference in days
        $totalDays = $hireDate->diffInDays($now);

        // Calculate full years and remaining days
        $years = intdiv($totalDays, 365); // Integer division to get full years
        $remainingDays = $totalDays % 365; // Remaining days after full years

        return "{$years} years and {$remainingDays} days";
    }




    ////this used to vaidate the nic and get the birthdate and gender. i am not use this function now because i am going to store the nic in users table and store the gender and birthdate also there
    public static function extractNICInfo($nic)
    {
    $nic = strtoupper($nic);//Converts the NIC to uppercase letters.So if someone enters v or x in lowercase, it becomes V or X.

    if (preg_match('/^\d{9}[VX]$/i', $nic))  //This line uses regular expression to check if the NIC:Starts with 9 digits Ends with V or X,^ and $ mean the pattern must match the entire string,The i flag makes it case-insensitive
    {               
        $year = 1900 + intval(substr($nic, 0, 2));//intval function is used to convert a string to an integer //First 2 digits (e.g., 86) and adds 1900 → year becomes 1986
        $dayOfYear = intval(substr($nic, 2, 3));// //Next 3 digits (e.g., 123) → day of the year (e.g., 123rd day = May 3rd)
    } 
    
    elseif (preg_match('/^\d{12}$/', $nic))//^ → Start of the string ,\d{12} → Exactly 12 digits  $ → End of the string  
    {
        $year = intval(substr($nic, 0, 4));//This extracts the first 4 characters from the NIC
        $dayOfYear = intval(substr($nic, 4, 3));//This gets the next 3 digits starting from position 4 (the 5th character in the string)
    } 
    else 
    {
        return ['error' => 'Invalid NIC format'];
    }

    if ($dayOfYear > 500) //This checks whether the value of $dayOfYear is greater than 500.//In Sri Lanka's NIC, 500 is added to the day number if the person is female.
    {
        $gender = 'Female';
        $dayOfYear -= 500;
    } else {
        $gender = 'Male';
    }

    $birthDate = \Carbon\Carbon::createFromDate($year, 1, 1)->addDays($dayOfYear - 1)->format('Y-m-d');

    return [
        'gender' => $gender,
        'birth_date' => $birthDate
    ];
    }

}
