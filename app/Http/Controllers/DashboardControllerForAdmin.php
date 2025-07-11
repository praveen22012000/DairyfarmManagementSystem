<?php

namespace App\Http\Controllers;
use App\Models\AnimalDetail;
use Illuminate\Http\Request;
use App\Models\ProductionMilk;
use App\Models\PurchaseFeedItems;
use App\Models\Feed;
use Carbon\Carbon;
use App\Models\RetailorOrder;
use App\Models\MilkProduct;
use App\Models\ManufacturerProduct;


class DashboardControllerForAdmin extends Controller
{
    //
    public function index()
    {
    // Total animals (you can also filter 'alive' if needed)
    $totalAnimals = AnimalDetail::where('status', 'alive')->count();

    // Total amont of Milk on the Stock
    $totalMilkInStock= ProductionMilk::sum('stock_quantity');

    // this calculate the total amount of milk in today
    $todayMilk = ProductionMilk::whereDate('production_date', Carbon::today())->sum('Quantity_Liters');

    //this function is used to calcualte the animals born in this week
    $weeklyAnimals= AnimalDetail::whereBetween('animal_birthdate',[
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()  
                    ])->count();


    $weeklyRetailorOrders= RetailorOrder::whereBetween('ordered_date',[
                             Carbon::now()->startOfWeek(),
                            Carbon::now()->startOfWeek(),

    ])->count();
    /*
    this code is used to calculate the total amount of milk in a specific week
    $weeklyMilk = ProductionMilk::whereBetween('production_date', [
                    Carbon::now()->startOfWeek(),  // Start of the week (Monday)
                    Carbon::now()->endOfWeek()     // End of the week (Sunday)
                ])
              ->sum('Quantity_Liters');*/

    /*
     //this code is used to calculate the total amount of milk production in a specific month
        $monthlyMilk = ProductionMilk::whereMonth('production_date', Carbon::now()->month)
                             ->whereYear('production_date', Carbon::now()->year)
                             ->sum('Quantity_Liters');

    */
    //this used to calculate the total amount of feed in the stock with feed_id=1
    $total_grain_Feed = PurchaseFeedItems::where('feed_id','1')->sum('stock_quantity');
    $feed_grain = Feed::find(1);              
   //this used to calculate the total amount of feed in the stock with feed_id=2
    $total_wheat_Feed = PurchaseFeedItems::where('feed_id','2')->sum('stock_quantity');



    //this get the milk product yogurt
    $yogurt = MilkProduct::find(1);
    //This calculate the total amount of yougurts in the stock
    $total_yogurt_stock = ManufacturerProduct::where('product_id','1')->sum('stock_quantity');

    $cheese = MilkProduct::find(4);
    $total_cheese_stock  = ManufacturerProduct::where('product_id','5')->sum('stock_quantity');

  

    //the total below code is used to genrate the chart
   // Get all delivered orders with their items
    $deliveredOrders = RetailorOrder::with('retailor_order_item')
        ->where('status', 'Delivered')
        ->whereYear('ordered_date', Carbon::now()->year)
        ->get();



         
    // Prepare monthly product sales
    $monthlySales = [];

    foreach (range(1, 12) as $month) {
        $monthlySales[$month] = 0;
    }

    foreach ($deliveredOrders as $order) {
        $month = Carbon::parse($order->ordered_date)->month;

        foreach ($order->retailor_order_item as $item) {
            $monthlySales[$month] += $item->ordered_quantity;
        }
    }

    // Prepare labels and data for the chart
    $labels = [];
    $sales = [];

    foreach (range(1, 12) as $month) {
        $labels[] = Carbon::create()->month($month)->format('M');
        $sales[] = $monthlySales[$month];
    }


    return view('dashboard',['totalAnimals'=>$totalAnimals,'totalMilkInStock'=>$totalMilkInStock,'todayMilk'=>$todayMilk,'total_grain_Feed'=>$total_grain_Feed,'feed_grain'=>$feed_grain,'total_wheat_Feed'=>$total_wheat_Feed,'sales'=>$sales,'labels'=>$labels,'weeklyAnimals'=>$weeklyAnimals,'weeklyRetailorOrders'=>$weeklyRetailorOrders,'yogurt'=>$yogurt,'total_yogurt_stock'=>$total_yogurt_stock,'cheese'=>$cheese,'total_cheese_stock'=>$total_cheese_stock]);
    }
}
