<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MonthlySalaryAssignment;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonthlySalaryAssignmentNotification;
use App\Models\RoleSalary;

class MonthlySalaryAssignmentController extends Controller
{
    //

    public function index()
    {
        $monthly_salary_assignments= MonthlySalaryAssignment::with('user')->get();

        return view('monthly_salary.index',['monthly_salary_assignments'=>$monthly_salary_assignments]);
    }

    public function create()
    {
        // $targetRoleIds = [2];

        $targetRoleIds= RoleSalary::pluck('role_id');

      //  dd($targetRoleIds);
         $users = User::whereIn('role_id',$targetRoleIds)->get();

         
        return view('monthly_salary.create',['users'=>$users]);
    }

    public function store(Request $request)
    {
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'salary_month' => [
            'required',
            'date_format:Y-m',//The value must match the format YYYY-MM (e.g., 2025-07)
            function ($attribute, $value, $fail) //This is an anonymous function (a function without a name), which Laravel will run automatically during validation
            {
                //“Convert the input string like '2025-08' into a date object like 2025-08-01”, // startOfMonth()->This sets the date to the first day of the month.//gt(now()->startOfMonth())  This trims the current date to the first day of the month.
                if (Carbon::createFromFormat('Y-m', $value)->startOfMonth()->gt(now()->startOfMonth())) //This line checks if the user entered a future month.
                {
                    // $fail is a special function provided by Laravel when you're writing a custom validation rule.
                    $fail('The salary month must be this month or a past month.');
                }
            },
        ],
        'custom_salary' => 'nullable|numeric|min:1',
    ]);

    //It takes the user input for salary_month (which comes as a string like "2025-07"), converts it to a standard date format representing the first day of that month,
    $salaryMonth = Carbon::createFromFormat('Y-m', $request->salary_month)->startOfMonth()->toDateString();

    //Checks if a record already exists in the monthly_salary_assignments table for a given user and salary month.
    $exists = MonthlySalaryAssignment::where('user_id', $request->user_id)
                ->where('salary_month', $salaryMonth)
                ->exists();//This is a special method that checks if any records match the query.and it returns true or false

    if ($exists) 
    {
        return redirect()->back()
               ->withInput() // THIS IS CRUCIAL
               ->withErrors(['This user has already been paid for this month.']);
    }

    
    $user = User::with('role')->findOrFail($request->user_id);
    $amount = $request->custom_salary ?? $user->role->role_salary->salary;

    $monthly_salary_assignment=MonthlySalaryAssignment::create([
        'user_id' => $user->id,
        'amount_paid' => $amount,
        'salary_month' => $request->salary_month . '-01',
        'paid_at' => now(),
    ]);

      Mail::to('pararajasingampraveen22@gmail.com')->send(new MonthlySalaryAssignmentNotification($monthly_salary_assignment));
     return redirect()->route('monthly_salary_assign.list')->with('success', 'Employee salary record saved successfully!');
    }

    public function edit(MonthlySalaryAssignment $monthlysalaryassign)
    {

           $targetRoleIds= RoleSalary::pluck('role_id');

         $users = User::whereIn('role_id',$targetRoleIds)->get();

         

         
        return view('monthly_salary.edit',['users'=>$users,'monthlysalaryassign'=>$monthlysalaryassign]);

    }

    public function update(Request $request, MonthlySalaryAssignment $monthlysalaryassign)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary_month' => [
                                'required',
                                'date_format:Y-m',//Ensures the input is in the YYYY-MM format.Example of valid values: 2025-01, 2024-12
                                function ($attribute, $value, $fail) //This is an anonymous function (closure) used as a custom validation rule.
                                {
                                    //This creates a Carbon date object from the input string // ->startOfMonth() This ensures the date becomes the first day of that month. //"Is the salary month greater than (i.e., after) the current month?"
                                        if (Carbon::createFromFormat('Y-m', $value)->startOfMonth()->gt(now()->startOfMonth())) ////Ensures salary is not paid for a future month
                                        {
                                            $fail('The salary month must be this month or a past month.');
                                        }
                                },
                            ],
                            'custom_salary' => 'nullable|numeric|min:1',
            ]);

    // Convert YYYY-MM to YYYY-MM-DD format
    $salaryMonth = $request->salary_month . '-01';

    // Check for duplicates (excluding current record)//To check whether this user already has a salary record for the same month,but ignore the current record being edited
    $exists = MonthlySalaryAssignment::where('user_id', $request->user_id)
              ->where('salary_month', $salaryMonth)
              ->where('id', '!=', $monthlysalaryassign->id)//but ignore the current record being edited.
              ->exists();

    if ($exists) 
    {
        return redirect()->back()
               ->withInput()
               ->withErrors(['This user has already been paid for this month.']);
    }

    $user = User::with('role')->findOrFail($request->user_id);
    $amount = $request->custom_salary ?? $user->role->role_salary->salary;

    $monthlysalaryassign->update([
        'user_id' => $request->user_id,
        'salary_month' => $salaryMonth, // Use the converted date
        'amount_paid' => $amount,
    ]);

         Mail::to('pararajasingampraveen22@gmail.com')->send(new MonthlySalaryAssignmentNotification($monthlysalaryassign));

    return redirect()->route('monthly_salary_assign.list')->with('success', 'Employee salary record updated successfully!');
    }

}