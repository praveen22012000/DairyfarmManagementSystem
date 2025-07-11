<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PurchaseFeedItems;
use App\Models\PurchaseVaccineItems;
use App\Models\AnimalDetail;
use App\Models\ProductionMilk;

class DashboardForGeneralManager extends Controller
{
    //
    public function getMonthlyDisposedFeedQuantities()//this function is used to calculate the total amount of each feed disposed in the month
    {
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                $disposedFeeds = DB::table('dispose_feed_items')
                                    ->join('dispose_feed_detaills', 'dispose_feed_items.dispose_feed_detail_id', '=', 'dispose_feed_detaills.id')
                                    ->join('purchase_feed_items', 'dispose_feed_items.purchase_feed_item_id', '=', 'purchase_feed_items.id')
                                    ->join('feeds', 'purchase_feed_items.feed_id', '=', 'feeds.id')
                                    ->whereBetween('dispose_feed_detaills.dispose_date', [$startOfMonth, $endOfMonth])
                                    ->select('feeds.feed_name', DB::raw('SUM(dispose_feed_items.dispose_quantity) as total_disposed_quantity'))
                                    ->groupBy('feeds.feed_name')
                                    ->get();

                return $disposedFeeds;
    }

    public function getMonthlyDisposedVaccineQuantities()
    {
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                $disposedVaccines = DB::table('dispose_vaccine_items')
                                        ->join('dispose_vaccine_details','dispose_vaccine_items.dispose_vaccine_detail_id','=','dispose_vaccine_details.id')
                                        ->join('purchase_vaccine_items','dispose_vaccine_items.purchase_vaccine_item_id','=','purchase_vaccine_items.id')
                                        ->join('vaccines','purchase_vaccine_items.vaccine_id','=','vaccines.id')
                                        ->whereBetween('dispose_vaccine_details.dispose_date',[$startOfMonth, $endOfMonth])
                                        ->select('vaccines.vaccine_name',DB::raw('SUM(dispose_vaccine_items.dispose_quantity) as total_disposed_vaccine_quantity'))
                                        ->groupBy('vaccines.vaccine_name')
                                        ->get();
                return $disposedVaccines;
    }

    public function totalMonthlyCostForFeed()
    {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $total_monthly_feed_cost = DB::table('purchase_feed_items')
                                ->join('purchase_feeds','purchase_feed_items.purchase_id','=','purchase_feeds.id')
                                ->join('feeds','purchase_feed_items.feed_id','=','feeds.id')
                                ->whereBetween('purchase_feeds.purchase_date',[$startOfMonth, $endOfMonth])
                                ->select('feeds.feed_name',DB::raw('SUM(purchase_feed_items.purchase_quantity * purchase_feed_items.unit_price) as total_amount_for_feed'))
                                ->groupBy('feeds.feed_name')
                                ->get();
            return $total_monthly_feed_cost;
    }

    public function totalMonthlyCostForVaccine()
    {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $total_monthly_vaccine_cost = DB::table('purchase_vaccine_items')
                                ->join('purchase_vaccines','purchase_vaccine_items.purchase_id','=','purchase_vaccines.id')
                                ->join('vaccines','purchase_vaccine_items.vaccine_id','=','vaccines.id')
                                ->whereBetween('purchase_vaccines.purchase_date',[$startOfMonth, $endOfMonth])
                                ->select('vaccines.vaccine_name',DB::raw('SUM(purchase_vaccine_items.purchase_quantity * purchase_vaccine_items.unit_price) as total_amount_for_vaccine'))
                                ->groupBy('vaccines.vaccine_name')
                                ->get();
            return $total_monthly_vaccine_cost;
    }


    public function index()
    {
            $total_feed_grain_items = PurchaseFeedItems::where('feed_id','1')->sum('stock_quantity');//this calculates the total amount Feed (grains) in the stock
            $total_feed_wheat_items = PurchaseFeedItems::where('feed_id','2')->sum('stock_quantity');// this calculates the total amount of Wheat in the stock

      
            $disposedFeeds = $this->getMonthlyDisposedFeedQuantities();// this calculates the total amount of feeds disposed in this month
            $disposedVaccines =$this->getMonthlyDisposedVaccineQuantities();// this calculates the total amount of vaccines disposed in this month

            $total_vaccine_rubella_items = PurchaseVaccineItems::where('vaccine_id','1')->sum('stock_quantity');
            $total_vaccine_rabies_items = PurchaseVaccineItems::where('vaccine_id','2')->sum('stock_quantity');


            $Total_monthly_Feed_cost =$this->totalMonthlyCostForFeed();

            $Total_monthly_Vaccine_cost = $this->totalMonthlyCostForVaccine();

            $totalAnimals = AnimalDetail::where('status', 'alive')->count();

            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

             $total_milk_production_of_the_month = ProductionMilk::whereBetween('production_date',[$startOfMonth,$endOfMonth])->sum('Quantity_Liters');


                $year = Carbon::now()->year;
              // Fetch data grouped by month
                $productions = ProductionMilk::whereYear('production_date', $year)->get();

                $monthlyData = array_fill_keys([
                                'January','February','March','April','May','June',
                                'July','August','September','October','November','December'
                                ], 0);

                foreach ($productions as $record) 
                {
                        $month = Carbon::parse($record->production_date)->format('F');//This line is used to extract the month name (like "January", "February", etc.) from a date.
                        $monthlyData[$month] += $record->Quantity_Liters;
                }

              
      
        return view('my_dashboards.gm',['total_feed_wheat_items'=>$total_feed_wheat_items,'total_feed_grain_items'=>$total_feed_grain_items,'disposedFeeds'=>$disposedFeeds,'disposedVaccines'=>$disposedVaccines,'total_vaccine_rubella_items'=>$total_vaccine_rubella_items,'total_vaccine_rabies_items'=>$total_vaccine_rabies_items,'Total_monthly_Feed_cost'=>$Total_monthly_Feed_cost,'Total_monthly_Vaccine_cost'=>$Total_monthly_Vaccine_cost,'totalAnimals'=>$totalAnimals,'total_milk_production_of_the_month'=>$total_milk_production_of_the_month,'monthlyData'=>$monthlyData]);
    }
  

    
}
