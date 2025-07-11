<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TaskAssignment;
use App\Models\RetailorOrder;
use App\Models\ProductionMilk;
use App\Models\ManufacturerProduct;
use Illuminate\Support\Facades\DB;

class DashboardControllerForFarmLabore extends Controller
{
    //
    public function getTotalTaskAssignments()//get the total amount of tasks assigned to the logged in farm labore
    {
        $userId = auth()->id();// this gets the currently logged in user;

        $user = User::findOrfail($userId);// this gets the logged in user instance 

        $farm_labore_id = $user->farm_labore->id;// this gets the logged in farm_labore_id

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $total_task_assignments = TaskAssignment::where('assigned_to',$farm_labore_id)
                                    ->whereBetween('assigned_date',[$startOfMonth, $endOfMonth])
                                ->count();

        return $total_task_assignments;

    }

    public function getTotalTaskCompletion()
    {
        $userId = auth()->id();// this gets the currently logged in user;

        $user = User::findOrfail($userId);// this gets the logged in user instance 

        $farm_labore_id = $user->farm_labore->id;// this gets the logged in farm_labore_id

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

         $total_task_completion = TaskAssignment::where('assigned_to',$farm_labore_id)
                                    ->whereBetween('assigned_date',[$startOfMonth, $endOfMonth])
                                    ->where('status','approved')
                                    ->count();
        return $total_task_completion;

    }

    public function getTotalTaskRejection()
    {
        $userId = auth()->id();// this gets the currently logged in user;

        $user = User::findOrfail($userId);// this gets the logged in user instance 

        $farm_labore_id = $user->farm_labore->id;// this gets the logged in farm_labore_id

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $total_task_rejection = TaskAssignment::where('assigned_to',$farm_labore_id)
                                ->whereBetween('assigned_date',[$startOfMonth, $endOfMonth])
                                ->where('status','rejected')
                                ->count();

        return $total_task_rejection;

    }

    public function getTotalRetailorOrderDelivery()
    {
        $userId = auth()->id();// this gets the currently logged in user;

        $user = User::findOrfail($userId);// this gets the logged in user instance 

        $farm_labore_id = $user->farm_labore->id;// this gets the logged in farm_labore_id

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $total_retailor_orders_delivered = RetailorOrder::where('delivery_person_id',$farm_labore_id)
                                ->where('status','Delivered')
                                ->whereBetween('ordered_date',[$startOfMonth, $endOfMonth])
                                ->count();
                    
        return $total_retailor_orders_delivered;

    }

    public function getTotalMilkFromAnimals()
    {
          $userId = auth()->id();// this gets the currently logged in user;

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();


          $total_milk_from_animal = ProductionMilk::where('user_id',$userId)
                                    ->whereBetween('production_date',[$startOfMonth, $endOfMonth])
                                    ->sum('production_milks.Quantity_Liters');

        return $total_milk_from_animal;
    }


    public function getManufacturedMilkProducts()
    {
          $userId = auth()->id();// this gets the currently logged in user;

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $manufactured_products = ManufacturerProduct::where('user_id',$userId)
                                ->join('milk_products','manufacturer_products.product_id','=','milk_products.id')
                                ->whereBetween('manufacture_date',[$startOfMonth, $endOfMonth])
                                ->select('milk_products.product_name', DB::raw('SUM(manufacturer_products.quantity) as total_production_quantity'))
                                ->groupby('milk_products.product_name')
                                ->get();

        return $manufactured_products;

    }

    public function getDisposedMilkProducts()
    {
        
          $userId = auth()->id();// this gets the currently logged in user;

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $disposed_products= DB::table('dispose_milk_products')
                            ->join('manufacturer_products','dispose_milk_products.manufacturer_product_id','=','manufacturer_products.id')
                            ->join('milk_products','manufacturer_products.product_id','=','milk_products.id')
                            ->whereBetween('date',[$startOfMonth, $endOfMonth])
                            ->where('dispose_milk_products.user_id',$userId)
                            ->select('milk_products.product_name',DB::raw('SUM(dispose_milk_products.dispose_quantity) as total_dispose_quantity'))
                            ->groupby('milk_products.product_name')
                            ->get();

        return $disposed_products;
    }

    public function index()
    {
        $total_farm_labores = User::where('role_id','5')->count();

       $userId = auth()->id();// this gets the currently logged in user;

       $user = User::findOrfail($userId);// this gets the logged in user instance 

       $farm_labore_id = $user->farm_labore->id;// this gets the logged in farm_labore_id

       $Total_Task_Assignments = $this->getTotalTaskAssignments();// this line gets the total task assignments

        $Total_Task_completion = $this->getTotalTaskCompletion();// this line gets the total task complete by the labores
  
        $Total_Task_Rejection = $this->getTotalTaskRejection();// this line gets the total tasks rejected by farm labores

        $total_retailor_orders_delivered = $this->getTotalRetailorOrderDelivery();//this line gets the total retailor orders delivered by the farm labore

        $total_milk_from_animal = $this->getTotalMilkFromAnimals();// this line gets the total amount of milk get from the animals

        $total_milk_product_manufacturing = $this->getManufacturedMilkProducts();// this line gets the amount of total milk products 

        $total_disposed_products = $this->getDisposedMilkProducts();// this line gets the milk products  disposed by the farm labores

        
    //the below code is used to generate the chart in the dashboard
        
           $year = Carbon::now()->year;// this line gets the current year

           $task_assignments = TaskAssignment::whereYear('assigned_date',$year)//this gets the animals born in the current year
                                    ->get();

         


         $monthlyData = array_fill_keys([
                                'January','February','March','April','May','June',
                                'July','August','September','October','November','December'
                                ], 0);

        foreach($task_assignments as $task_assignment)
        {
            $month = Carbon::parse($task_assignment->assigned_date)->format('F');//This line is extracting the month name (like "January", "February", etc.) from a date, using Laravel's Carbon date library.

              $monthlyData[$month] += 1;
        }                       


        return view('my_dashboards.farm_labore_dashboard',['total_farm_labores'=>$total_farm_labores,'Total_Task_Assignments'=>$Total_Task_Assignments,'Total_Task_completion'=>$Total_Task_completion,'Total_Task_Rejection'=>$Total_Task_Rejection,'total_retailor_orders_delivered'=>$total_retailor_orders_delivered,'total_milk_from_animal'=>$total_milk_from_animal,'total_milk_product_manufacturing'=>$total_milk_product_manufacturing,'total_disposed_products'=>$total_disposed_products,'monthlyData'=>$monthlyData]);
    }
}
