<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\RetailorOrder;
use App\Models\User;
use App\Models\PurchaseFeedPayment;
use App\Models\PurchaseVaccinePayments;
use App\Models\RetailorOrderItems;
use Illuminate\Support\Facades\DB;
class DashboardControllerForSalesManager extends Controller
{
    //
//this  function is used to calculate the total amount of ordered products in this month
    public function getTotalOrderedQuantity()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

         $total_order_products = DB::table('retailor_order_items')
                                        ->join('retailor_orders','retailor_order_items.order_id','=','retailor_orders.id' )
                                        ->join('milk_products','retailor_order_items.product_id','=','milk_products.id')
                                        ->whereBetween('ordered_date',[$startOfMonth,$endOfMonth])
                                        ->select('milk_products.product_name',DB::raw('SUM(retailor_order_items.ordered_quantity) as total_amount_of_order_products'))
                                        ->groupBy('milk_products.product_name')
                                        ->get();

        return $total_order_products;
    }

    public function getTotalSalesQuantity()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $total_sales_products = DB::table('retailor_order_items')
                                        ->join('retailor_orders','retailor_order_items.order_id','=','retailor_orders.id' )
                                        ->join('milk_products','retailor_order_items.product_id','=','milk_products.id')
                                        ->whereBetween('ordered_date',[$startOfMonth,$endOfMonth])
                                        ->where('status','Delivered')
                                        ->select('milk_products.product_name',DB::raw('SUM(retailor_order_items.ordered_quantity) as total_amount_of_sales_products'))
                                        ->groupBy('milk_products.product_name')
                                        ->get();

        return $total_sales_products;
    }


     public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();


        $no_of_retailor_orders = RetailorOrder::whereBetween('ordered_date',[$startOfMonth,$endOfMonth])->count();

        $no_of_retailors = User::where('role_id','3')->count();

        $no_of_feed_payments = PurchaseFeedPayment::whereBetween('payment_date',[$startOfMonth,$endOfMonth])->count();

        $no_of_vaccine_payments = PurchaseVaccinePayments::whereBetween('payment_date',[$startOfMonth,$endOfMonth])->count();

        $total_paid_for_vaccine = PurchaseVaccinePayments::whereBetween('payment_date',[$startOfMonth,$endOfMonth])->sum('payment_amount');

        $total_paid_for_feed = PurchaseFeedPayment::whereBetween('payment_date',[$startOfMonth,$endOfMonth])->sum('payment_amount');

        $total_saels_amount = RetailorOrder::whereBetween('ordered_date',[$startOfMonth,$endOfMonth])->sum('total_payable_amount');

        $product_ids = RetailorOrderItems::distinct()->pluck('product_id');//this line gets the unique product ids

        $Total_Order_Products = $this->getTotalOrderedQuantity();

        $Total_Sales_Products = $this->getTotalSalesQuantity();

        //the below code is to calcuate the profit

           $year = Carbon::now()->year;

           $sales = RetailorOrder::whereYear('ordered_date',$year)
                    ->where('status','Delivered')
                    ->get();


         $monthlyData = array_fill_keys([
                                'January','February','March','April','May','June',
                                'July','August','September','October','November','December'
                                ], 0);

        foreach($sales as $sale)
        {
            $month = Carbon::parse($sale->ordered_date)->format('F');

              $monthlyData[$month] += $sale->total_payable_amount;
        }


        return view('my_dashboards.sm',['no_of_retailor_orders'=>$no_of_retailor_orders,'no_of_retailors'=>$no_of_retailors,'no_of_feed_payments'=>$no_of_feed_payments,'no_of_vaccine_payments'=>$no_of_vaccine_payments,'total_paid_for_feed'=>$total_paid_for_feed,'total_paid_for_vaccine'=>$total_paid_for_vaccine,'Total_Order_Products'=>$Total_Order_Products,'Total_Sales_Products'=>$Total_Sales_Products,'monthlyData'=>$monthlyData]);
    }
  
}
