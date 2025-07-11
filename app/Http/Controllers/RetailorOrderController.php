<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MilkProduct;
use App\Models\RetailorOrder;
use App\Models\RetailorOrderItems;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RetailorOrderNotification;
use App\Mail\RetailorOrderUpdateNotification;

use App\Models\ManufacturerProduct;
use App\Mail\LowStockProductWhenDeliveryNotification;

use Barryvdh\DomPDF\Facade\Pdf;


class RetailorOrderController extends Controller
{
    //the below function is used to calculate the total retailor orders
    public function totalDeliverOrdersReport(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $totalDeliveredOrders = [];

         if($start && $end)
        {
             $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $totalDeliveredOrders = DB::table('retailor_order_items')
            ->join('retailor_orders','retailor_order_items.order_id','=','retailor_orders.id')
            ->join('milk_products','retailor_order_items.product_id','=','milk_products.id')
             ->whereBetween('retailor_orders.ordered_date', [$start, $end])
             ->where('retailor_orders.status','Delivered')
             ->select(
                    'milk_products.product_name',
                    DB::raw('SUM(retailor_order_items.ordered_quantity) as total_delivered_quantity'),
           
                    )->groupBy('milk_products.product_name')
            ->get();
        } 

         return view('reports.delivered_retailor_orders', compact('totalDeliveredOrders', 'start', 'end'));
    }

    public function totalDeliverOrdersReportDownloadPDF(Request $request)
    {
         $start = $request->start_date;
        $end = $request->end_date;

        $totalDeliveredOrders = [];

         if($start && $end)
        {
             $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $totalDeliveredOrders = DB::table('retailor_order_items')
            ->join('retailor_orders','retailor_order_items.order_id','=','retailor_orders.id')
            ->join('milk_products','retailor_order_items.product_id','=','milk_products.id')
             ->whereBetween('retailor_orders.ordered_date', [$start, $end])
             ->where('retailor_orders.status','Delivered')
             ->select(
            'milk_products.product_name',
            DB::raw('SUM(retailor_order_items.ordered_quantity) as total_delivered_quantity')
        )->groupBy('milk_products.product_name')
        ->get();
        } 

         $pdfInstance = Pdf::loadView('reports_pdf.delivered_retailor_orders_pdf', compact('totalDeliveredOrders', 'start', 'end'));
         return $pdfInstance->download('DeliveredMilkProdcuts.pdf');
    }

    public function index()
    {
          $user = Auth::user();

    // Admin or Manager: can view all orders
    if ($user->role_id == 1 || $user->role_id == 7) {
        $retailor_orders = RetailorOrder::with(['user', 'farm_labore'])->latest()->get();
    }
    // Retailor: only their own orders
    elseif ($user->role_id == 3) {
        $retailor_orders = RetailorOrder::with(['user', 'farm_labore'])
                                ->where('retailor_id', $user->id)
                                ->latest()
                                ->get();
    }
    // Farm Labore: only orders assigned to them
    elseif ($user->role_id == 5) {
        $retailor_orders = RetailorOrder::with(['user', 'farm_labore'])
                                ->where('delivery_person_id', $user->farm_labore->id)
                                ->latest()
                                ->get();
    }
    else {
      abort(403, 'Unauthorized action.');
    }

    return view('retailor_orders.index',['retailor_orders'=>$retailor_orders]);

    }
    
    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1,3])) 
        {
            abort(403, 'Unauthorized action.');
        }

        

        $milk_products=MilkProduct::with('retailor_order_item')->get();

        return view('retailor_orders.create',['milk_products'=>$milk_products]);
    }

    public function store(Request $request)
    {

        if (!in_array(Auth::user()->role_id, [1,3])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([

            'delivery_address'=>'required',

            'product_id'=>'required|array',
            'product_id.*'=>'required|exists:milk_products,id',

            'ordered_quantity'=>'required|array',
            'ordered_quantity.*'=>'required|numeric|min:1',
            

        ]);

                // Start transaction
            DB::beginTransaction();

            // Create the order
            $order = new RetailorOrder();

            $order->retailor_id = auth()->id();
            $order->delivery_address = $request->delivery_address;
        
            $order->ordered_date=now();

            $order->status = 'Pending';
            $order->total_amount = 0;
            $order->save();

            $totalAmount = 0;

            $errors = [];

            foreach ($request->product_id as $index => $productId) 
            {
                $product = MilkProduct::findOrFail($productId);
                $orderedQuantity = $request->ordered_quantity[$index];
                
               
                
        
                // Create order item
                $orderItem = new RetailorOrderItems();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $productId;
                $orderItem->ordered_quantity = $orderedQuantity;
                $orderItem->unit_price = $product->unit_price;
                $orderItem->save();
        
                $totalAmount += $product->unit_price * $orderedQuantity;
                
            }

            if($totalAmount > 10000)
            {
                 $discount=$totalAmount * 0.3;
                $payable_amount=$totalAmount-$discount;
            }
            else if($totalAmount > 5000)
            {
                $discount=$totalAmount * 0.2;
                $payable_amount=$totalAmount-$discount;
            }
            else
            {
                 $discount=$totalAmount * 0;
                $payable_amount=$totalAmount-$discount;
            }
        
            // Update the order with calculated total amount
            $order->total_amount = $totalAmount;
            $order->discount_amount= $discount;
            $order->total_payable_amount= $payable_amount;
            
            $order->save();
        
            // Commit transaction if everything is successful
            DB::commit();

          
          //the below code is used for notification when low stock
            // Get all ordered items for this order
            $orderItems = $order->retailor_order_item; // plural: items

            // Extract product_ids from the collection
            $productIds = $orderItems->pluck('product_id');


            foreach($productIds as $pro_id)
            {
                $milk_product = MilkProduct::findOrfail($pro_id);

            
                $available_stock = ManufacturerProduct::where('product_id',$pro_id)->sum('stock_quantity');

                if($available_stock < 10)
                {
                     Mail::to('pararajasingampraveen22@gmail.com')->send(new LowStockProductWhenDeliveryNotification($milk_product,$available_stock,));
                }
            }

            $retailor_order_items=RetailorOrderItems::where('order_id',$order->id)->get();

             // Send email to general manager
                Mail::to('pararajasingampraveen22@gmail.com')->send(new RetailorOrderNotification($order,$retailor_order_items));
            return redirect()->route('retailor_order_items.list')->with('success', 'Retailor Order Saved successfully.');
    }

    public function view(RetailorOrder $retailororder)
    {
        if (!in_array(Auth::user()->role_id, [1,3])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $milk_products=MilkProduct::with('retailor_order_item')->get();

        return view('retailor_orders.view',['milk_products'=>$milk_products,'retailororder'=>$retailororder, 'order_items' => $retailororder->retailor_order_item]);
    }

    public function edit(RetailorOrder $retailororder)
    {
        if (!in_array(Auth::user()->role_id, [1,3])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $milk_products=MilkProduct::with('retailor_order_item')->get();

        return view('retailor_orders.edit',['milk_products'=>$milk_products,'retailororder'=>$retailororder, 'order_items' => $retailororder->retailor_order_item]);
    }

    public function update(RetailorOrder $retailororder,Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1,3])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([

            'delivery_address'=>'required',

            'product_id'=>'required|array',
            'product_id.*'=>'required|exists:milk_products,id',

            'ordered_quantity'=>'required|array',
            'ordered_quantity.*'=>'required|numeric|min:1',
        ]);

        $errors = [];

         // Start transaction
        DB::beginTransaction();


        $retailororder->update([

            'delivery_address'=>$request->delivery_address,
            'retailor_id'=>auth()->id(),
            'ordered_date'=>now(),
            'status'=>'Pending',
            'total_amount'=> 0
        ]);

         // Delete old order items
        $retailororder->retailor_order_item()->delete();

        $totalAmount = 0;


        foreach ($request->product_id as $index => $productId) 
        {
            $product = MilkProduct::findOrFail($productId);
            $orderedQuantity = $request->ordered_quantity[$index];
            
           
            $retailororder->retailor_order_item()->create([
                'product_id'=>$productId,
                'ordered_quantity'=>$orderedQuantity,
                'unit_price'=>$product->unit_price,
                'order_id'=>$retailororder->id
            ]);
    
        
            $totalAmount += $product->unit_price * $orderedQuantity;
        }

        $retailororder->update(['total_amount'=>$totalAmount]);

            // Commit transaction if everything is successful
            DB::commit();

            $retailor_order_items=RetailorOrderItems::where('order_id',$retailororder->id)->get();

              // Send email to general manager
                Mail::to('pararajasingampraveen22@gmail.com')->send(new RetailorOrderUpdateNotification($retailororder,$retailor_order_items));

        return redirect()->route('retailor_order_items.list')->with('success', 'Retailor Order updated successfully.');
    }
}
