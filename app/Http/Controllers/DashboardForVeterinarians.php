<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimalDetail;
use App\Models\AnimalCalvings;
use App\Models\BreedingEvents;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardForVeterinarians extends Controller
{
    //
    public function vaccine_consumption_monthly()
    {
         $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $total_vaccine_consumption_monthly = DB::table('vaccine_consume_items')
                                            ->join('vaccines','vaccine_consume_items.vaccine_id','=','vaccines.id')
                                            ->join('vaccine_consume_details','vaccine_consume_items.vaccine_consume_detail_id','=','vaccine_consume_details.id')
                                               ->whereBetween('vaccination_date',[$startOfMonth,$endOfMonth])
                                               ->select('vaccines.vaccine_name',DB::raw('SUM(vaccine_consume_items.consumed_quantity) as total_amount_of_vaccine'))
                                               ->groupBy('vaccines.vaccine_name')
                                               ->get();

                return $total_vaccine_consumption_monthly;
    }
    
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Total animals (you can also filter 'alive' if needed)
        $totalAnimals = AnimalDetail::where('status', 'alive')->count();// this calculates the total animals in the farm

        $total_death_animals= AnimalDetail::where('status', 'deceased')->count();// this calculates the total animals death in the farm

        $total_no_of_female_animals = AnimalDetail::whereIn('animal_type_id',['1','2'])->count();//this calculates the total female animals in the farm 


        $total_no_of_male_animals = AnimalDetail::whereIn('animal_type_id',['3','4'])->count();// this calculates the total male animals in the farm

       // dd($total_no_of_male_animals);
                       
        $total_amount_of_calves = AnimalCalvings::whereBetween('calving_date',[$startOfMonth,$endOfMonth])->count();//this calculates the total amount of newly born animals

        $total_no_of_breeding_events = BreedingEvents::whereBetween('breeding_date',[$startOfMonth,$endOfMonth])->count();// this calcualtes the total number of breeding events

    //  
    //
    //
        $Total_Vaccine_Consumption = $this->vaccine_consumption_monthly();// this calcualtes the total amount of vaccines consumption

        $total_vaccinated_animals = DB::table('vaccine_consume_items')
                                    ->join('vaccine_consume_details','vaccine_consume_items.vaccine_consume_detail_id','=','vaccine_consume_details.id')
                                    ->join('animal_details','vaccine_consume_items.animal_id','=','animal_details.id')
                                    ->whereBetween('vaccination_date',[$startOfMonth,$endOfMonth])
                                    ->select('animal_details.animal_name',DB::raw('SUM(vaccine_consume_items.consumed_quantity) as total_amount_of_vaccine'))
                                    ->groupBy('animal_details.animal_name')
                                    ->get();

   

    //the below code is used to generate the chart in the dashboard
        
           $year = Carbon::now()->year;// this line gets the current year

           $animal_birth_details = AnimalDetail::whereYear('animal_birthdate',$year)//this gets the animals born in the current year
                                    ->get();


         $monthlyData = array_fill_keys([
                                'January','February','March','April','May','June',
                                'July','August','September','October','November','December'
                                ], 0);

        foreach($animal_birth_details as $animal_birth_detail)
        {
            $month = Carbon::parse($animal_birth_detail->animal_birthdate)->format('F');//This line is extracting the month name (like "January", "February", etc.) from a date, using Laravel's Carbon date library.

              $monthlyData[$month] += 1;
        }                       

      
       

        return view('my_dashboards.veterinarian',['totalAnimals'=>$totalAnimals,'total_death_animals'=>$total_death_animals,'total_no_of_female_animals'=>$total_no_of_female_animals,'total_no_of_male_animals'=>$total_no_of_male_animals,'total_amount_of_calves'=>$total_amount_of_calves,'total_no_of_breeding_events'=>$total_no_of_breeding_events,'Total_Vaccine_Consumption'=>$Total_Vaccine_Consumption,'total_vaccinated_animals'=>$total_vaccinated_animals,'monthlyData'=>$monthlyData]);
    }
}
