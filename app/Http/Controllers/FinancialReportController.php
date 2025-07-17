<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailorOrder;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
class FinancialReportController extends Controller
{
    //

    public function financialReport(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
             $start = $request->start_date;
            $end = $request->end_date;

          // Define default values to avoid undefined variable errors
            $milk_product_total_income = 0;
            $purchase_feed_items_expenses = 0;
            $purchase_vaccine_items_expenses = 0;
            $total_salary_expenses = 0;
            $final_value = 0;
            $total_income=0;
            $total_expenses=0;

         if ($start && $end) 
        {
            $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            ]);

     

            $milk_product_total_income = RetailorOrder::whereBetween('ordered_date',[$start,$end])
                                            ->where('status','Delivered')
                                            ->sum('total_payable_amount');

           

            $purchase_feed_items_expenses = DB::table('purchase_feed_items')
                                            ->join('purchase_feeds','purchase_feed_items.purchase_id','=','purchase_feeds.id')
                                            ->whereBetween('purchase_feeds.purchase_date',[$start,$end])
                                            ->select(DB::raw('SUM(purchase_feed_items.purchase_quantity * purchase_feed_items.unit_price) as total'))
                                            ->value('total');
                                    
                    

            $purchase_vaccine_items_expenses = DB::table('purchase_vaccine_items')
                                                ->join('purchase_vaccines','purchase_vaccine_items.purchase_id','=','purchase_vaccines.id')
                                                ->whereBetween('purchase_vaccines.purchase_date',[$start,$end])
                                                ->select(DB::raw('SUM(purchase_vaccine_items.purchase_quantity * purchase_vaccine_items.unit_price) as total'))
                                                ->value('total');

        
            $total_salary_expenses = DB::table('monthly_salary_assignments')
                                        ->whereBetween('salary_month',[$start,$end])
                                        ->sum('amount_paid');
                                        
            
            $total_income = $milk_product_total_income;

            $total_expenses = $purchase_feed_items_expenses + $purchase_vaccine_items_expenses + $total_salary_expenses;

            $final_value = $total_income - $total_expenses;

            if($final_value >= 0)
            {
                $profit = $final_value;
            }

            else
            {
                $loss = $final_value;
            }
            
            
        }

        return view('reports.financial_report',compact('milk_product_total_income','purchase_feed_items_expenses','purchase_vaccine_items_expenses','total_salary_expenses','final_value','start','end','total_income','total_expenses'));
    }

    public function DownloadFianacialReport(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

         $start = $request->start_date;
        $end = $request->end_date;

          // Define default values to avoid undefined variable errors
    $milk_product_total_income = 0;
    $purchase_feed_items_expenses = 0;
    $purchase_vaccine_items_expenses = 0;
    $total_salary_expenses = 0;
    $final_value = 0;

         if ($start && $end) 
        {
            $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            ]);

     

            $milk_product_total_income = RetailorOrder::whereBetween('ordered_date',[$start,$end])
                                            ->where('status','Delivered')
                                            ->sum('total_payable_amount');

           

            $purchase_feed_items_expenses = DB::table('purchase_feed_items')
                                            ->join('purchase_feeds','purchase_feed_items.purchase_id','=','purchase_feeds.id')
                                            ->whereBetween('purchase_feeds.purchase_date',[$start,$end])
                                            ->select(DB::raw('SUM(purchase_feed_items.purchase_quantity * purchase_feed_items.unit_price) as total'))
                                            ->value('total');
                                    
                    

            $purchase_vaccine_items_expenses = DB::table('purchase_vaccine_items')
                                                ->join('purchase_vaccines','purchase_vaccine_items.purchase_id','=','purchase_vaccines.id')
                                                ->whereBetween('purchase_vaccines.purchase_date',[$start,$end])
                                                ->select(DB::raw('SUM(purchase_vaccine_items.purchase_quantity * purchase_vaccine_items.unit_price) as total'))
                                                ->value('total');

        
            $total_salary_expenses = DB::table('monthly_salary_assignments')
                                        ->whereBetween('salary_month',[$start,$end])
                                        ->sum('amount_paid');
                                        
            
            $total_income = $milk_product_total_income;

            $total_expenses = $purchase_feed_items_expenses + $purchase_vaccine_items_expenses + $total_salary_expenses;

            $final_value = $total_income - $total_expenses;

            if($final_value >= 0)
            {
                $profit = $final_value;
            }

            else
            {
                $loss = $final_value;
            }
            
            
        }

       $pdfInstance = Pdf::loadView('reports_pdf.financial_report_pdf', compact('milk_product_total_income','purchase_feed_items_expenses','purchase_vaccine_items_expenses','total_salary_expenses','final_value','start','end','total_income','total_expenses'));
        return $pdfInstance->download('Fianacial Report.pdf');
    }
}
