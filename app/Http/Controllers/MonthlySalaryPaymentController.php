<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MonthlySalaryPaymentController extends Controller
{
    //

   public function getEligibleEmployees(Request $request)
    {
    $month = $request->query('month'); // format: YYYY-MM

    $alreadyPaid = MonthlySalaryPayment::where('month', $month . '-01')->pluck('employee_id');

    $employees = User::with('role')
        ->whereNotIn('id', $alreadyPaid)
        ->get()
        ->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'role' => $employee->role->role_name ?? 'Unknown',
            ];
        });

    return response()->json($employees);
    }
    public function create()
    {
     //   $employees=User::where('role_id',[2,5,6,7])->get();

       return view('monthly_salary_payment.create');
    }


}
