<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RetailorOrder;

use Illuminate\Support\Facades\DB;

class DashboardForRetailor extends Controller
{
    //
    public function totalNoOfMilkProductOrders()
    {
        $user_id = auth()->id();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $total_orders = DB::table('retailor_order_items')
                        ->join('retailor_orders','retailor_order_items.order_id','=','retailor_orders.id')
                        ->join('milk_products','retailor_order_items.product_id','=','milk_products.id')
                        ->whereBetween('ordered_date',[$startOfMonth,$endOfMonth])
                        ->where('retailor_orders.status','Delivered')
                        ->where('retailor_orders.retailor_id',$user_id)
                        ->select('milk_products.product_name',DB::raw('SUM(retailor_order_items.ordered_quantity) as total_amount_of_products'))
                        ->groupby('milk_products.product_name')
                        ->get();

        return $total_orders;
    }

    public function totalAmountSpendForProducts()
    {
        $userId = auth()->id();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $total_amount = DB::table('retailor_order_items')
                                ->join('retailor_orders', 'retailor_order_items.order_id', '=', 'retailor_orders.id')
                                ->join('milk_products', 'retailor_order_items.product_id', '=', 'milk_products.id')
                                ->whereBetween('retailor_orders.ordered_date', [$startOfMonth, $endOfMonth])
                                ->where('retailor_orders.status', 'Delivered')
                                ->where('retailor_orders.retailor_id', $userId)
                                ->select('milk_products.product_name',DB::raw('SUM(retailor_order_items.ordered_quantity * retailor_order_items.unit_price) as total_amount_spent'))
                                ->groupBy('milk_products.product_name')
                                ->get();
        return $total_amount;

    }

    public function index()
    {

        $no_of_retailors = User::where('role_id','3')->count();

        $user_id = auth()->id();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $total_no_of_orders = RetailorOrder::where('retailor_id',$user_id)
                                ->whereBetween('ordered_date',[$startOfMonth, $endOfMonth])
                                ->count();


        $total_no_of_order_canceled = RetailorOrder::where('retailor_id',$user_id)
                                    ->whereBetween('ordered_date',[$startOfMonth, $endOfMonth])
                                    ->where('status','canceled')
                                    ->count();

        $total_no_of_order_rejected = RetailorOrder::where('retailor_id',$user_id)
                                    ->whereBetween('ordered_date',[$startOfMonth, $endOfMonth])
                                    ->where('status','Rejected')
                                    ->count();
                                  //  dd($total_no_of_order_canceld);

        $total_unpaid_orders = RetailorOrder::where('retailor_id',$user_id)
                                ->whereBetween('ordered_date',[$startOfMonth, $endOfMonth])
                                ->where('status','Approved')
                                ->where('payment_status','Unpaid')
                                ->count();

        $total_delivered_orders = RetailorOrder::where('retailor_id',$user_id)
                                 ->whereBetween('ordered_date',[$startOfMonth, $endOfMonth])
                                 ->where('status','Delivered')
                                 ->count();

        
        $total_orders = $this->totalNoOfMilkProductOrders();// this line gets the retailor orders

        $total_amount = $this->totalAmountSpendForProducts();// this line gets the amount spend for products


        $year = Carbon::now()->year;

        $sales = RetailorOrder::whereYear('ordered_date',$year)
                    ->where('status','Delivered')
                    ->where('retailor_id',$user_id)
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





        return view('my_dashboards.retailor_dashboard',['no_of_retailors'=>$no_of_retailors,'total_no_of_orders'=>$total_no_of_orders,'total_no_of_order_canceled'=>$total_no_of_order_canceled,'total_no_of_order_rejected'=>$total_no_of_order_rejected,'total_unpaid_orders'=>$total_unpaid_orders,'total_delivered_orders'=>$total_delivered_orders,'total_orders'=>$total_orders,'total_amount'=>$total_amount,'monthlyData'=>$monthlyData]);
    }
}
