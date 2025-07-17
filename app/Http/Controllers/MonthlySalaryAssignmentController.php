<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MonthlySalaryAssignment;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonthlySalaryAssignmentNotification;
use App\Models\RoleSalary;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class MonthlySalaryAssignmentController extends Controller
{
    //this is new.this calcualtes the salary spent for the each role between specific dates
    public function salaryReportPerRole(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $start = $request->start_date;
        $end = $request->end_date;

        $salaryPerRole = [];

        if ($start && $end) 
        {
            $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            ]);

            $salaryPerRole = DB::table('monthly_salary_assignments')

                            ->join('users','monthly_salary_assignments.user_id','=','users.id')
                            ->join('roles','users.role_id','=','roles.id')
                            ->whereBetween('monthly_salary_assignments.salary_month', [$start, $end])
                            ->select('roles.role_name',DB::raw('SUM(monthly_salary_assignments.amount_paid) as total_salary_per_role'))
                            ->groupBy('roles.role_name')
                            ->get();

         
            
        }

        
        return view('reports.salary_per_role_report',['start'=>$start,'end'=>$end,'salaryPerRole'=>$salaryPerRole]);

    }

    //this is the download pdf for above function
    public function salaryReportPerRolePDF(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $start = $request->start_date;
        $end = $request->end_date;

        $salaryPerRole = [];

        if ($start && $end) 
        {
            $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            ]);

            $salaryPerRole = DB::table('monthly_salary_assignments')

                            ->join('users','monthly_salary_assignments.user_id','=','users.id')
                            ->join('roles','users.role_id','=','roles.id')
                            ->whereBetween('monthly_salary_assignments.salary_month', [$start, $end])
                            ->select('roles.role_name',DB::raw('SUM(monthly_salary_assignments.amount_paid) as total_salary_per_role'))
                            ->groupBy('roles.role_name')
                            ->get();

         
            
        }

        $pdfInstance = Pdf::loadView('reports_pdf.salary_per_role_report_pdf', compact('salaryPerRole', 'start', 'end'));
         return $pdfInstance->download('Salary Allocation Report.pdf');
    }

    // this is for calcualte total salary spent for the specific role in months
    public function monthlySalaryPerSpecificRole(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $start = $request->start_date;
        $end = $request->end_date;
        $roleId = $request->role_id;

        $targetRoleIds = ['2','5','6','7'];
        $roles = Role::whereIn('id',$targetRoleIds)->get();

        $salaryPerMonth = [];

        if ($start && $end && $roleId) 
        {
            $request->validate
            ([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'role_id' => 'required|exists:roles,id'
            ]);

            $salaryPerMonth = DB::table('monthly_salary_assignments')

            ->join('users', 'monthly_salary_assignments.user_id', '=', 'users.id')
            ->where('users.role_id', $roleId)
            ->whereBetween('monthly_salary_assignments.salary_month', [$start, $end])
            ->select(
                DB::raw("DATE_FORMAT(salary_month, '%Y-%m') as month"),
                DB::raw('SUM(amount_paid) as total_salary')
                )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        }

       
        return view('reports.monthly_salary_specific_role',['roles'=>$roles,'start'=>$start,'end'=>$end,'salaryPerMonth'=>$salaryPerMonth]);
    }


     // this is for calcualte total salary spent for the specific role in months
    public function monthlySalaryPerSpecificRolePDF(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $start = $request->start_date;
        $end = $request->end_date;
        $roleId = $request->role_id;

       $targetRoleIds = ['2','5','6','7'];
        $roles = Role::whereIn('id',$targetRoleIds)->get();

   
        $salaryPerMonth = [];

        if ($start && $end && $roleId) 
        {
            $request->validate
            ([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'role_id' => 'required|exists:roles,id'
            ]);

            $salaryPerMonth = DB::table('monthly_salary_assignments')

            ->join('users', 'monthly_salary_assignments.user_id', '=', 'users.id')
            ->where('users.role_id', $roleId)
            ->whereBetween('monthly_salary_assignments.salary_month', [$start, $end])
            ->select(
                DB::raw("DATE_FORMAT(salary_month, '%Y-%m') as month"),
                DB::raw('SUM(amount_paid) as total_salary')
                )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

           
        }

      
         $pdfInstance = Pdf::loadView('reports_pdf.monthly_salary_specific_role_pdf',['roles'=>$roles,'start'=>$start,'end'=>$end,'salaryPerMonth'=>$salaryPerMonth]);
         return $pdfInstance->download('Salary Allocation by Role Report.pdf');
    }



    public function index()
    {
        $monthly_salary_assignments= MonthlySalaryAssignment::with('user')->get();

        return view('monthly_salary.index',['monthly_salary_assignments'=>$monthly_salary_assignments]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        // $targetRoleIds = [2];

        $targetRoleIds= RoleSalary::pluck('role_id');

      //  dd($targetRoleIds);
         $users = User::whereIn('role_id',$targetRoleIds)->get();

         
        return view('monthly_salary.create',['users'=>$users]);
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

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

      
            $pdf = \PDF::loadView('monthly_salary_payment_slips.monthly_slip', [
                'monthly_salary_assignment'=>$monthly_salary_assignment,
                'user' => $user
            ]);

          return $pdf->download('salary assignment slip' . $user->name . '.pdf');
    // return redirect()->route('monthly_salary_assign.list')->with('success', 'Employee salary record saved successfully!');
    }

    public function edit(MonthlySalaryAssignment $monthlysalaryassign)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $targetRoleIds= RoleSalary::pluck('role_id');

        $users = User::whereIn('role_id',$targetRoleIds)->get();

         

         
        return view('monthly_salary.edit',['users'=>$users,'monthlysalaryassign'=>$monthlysalaryassign]);

    }

    public function update(Request $request, MonthlySalaryAssignment $monthlysalaryassign)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
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

        $monthlysalaryassign->update
        ([
        'user_id' => $request->user_id,
        'salary_month' => $salaryMonth, // Use the converted date
        'amount_paid' => $amount,
        ]);

         Mail::to('pararajasingampraveen22@gmail.com')->send(new MonthlySalaryAssignmentNotification($monthlysalaryassign));

        return redirect()->route('monthly_salary_assign.list')->with('success', 'Employee salary record updated successfully!');
    }

    public function view(MonthlySalaryAssignment $monthlysalaryassign)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        
         $targetRoleIds= RoleSalary::pluck('role_id');

         $users = User::whereIn('role_id',$targetRoleIds)->get();
         
        return view('monthly_salary.view',['users'=>$users,'monthlysalaryassign'=>$monthlysalaryassign]);
    }

    public function destroy(MonthlySalaryAssignment $monthlysalaryassign)
    {
        if (!in_array(Auth::user()->role_id, [1])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $monthlysalaryassign->delete();

         return redirect()->route('monthly_salary_assign.list')->with('success', 'Employee salary record deleted successfully!');
    }

}