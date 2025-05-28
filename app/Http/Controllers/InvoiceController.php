<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RetailorOrder;

class InvoiceController extends Controller
{
    //
    public function generateInvoice($orderId)
    {
    $order = RetailorOrder::with('retailor_order_item')->findOrFail($orderId);

    $pdf = \PDF::loadView('retailor_invoices.invoice',['order'=>$order]);

    return $pdf->download('invoice_' . $order->id . '.pdf');
    }
}
