<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Models\Role;
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
       // $roles=Role::all();

       //this line is used to get the roles_name and id from the roles table
        $roles = Role::all()->pluck('role_name','id')->toArray();

        $roles['']='--Choose Your Role--';

   
        //this line is used to send the roles array ,get from above code,to the auth.register blade
        return view('auth.register',['roles'=>$roles]);

    }

    //the following code is used to extract gender and birthdate from the nic
    private function extractDetailsFromNIC($nic)
    {
    $year = '';
    $dayText = '';
    $gender = '';

    if (strlen($nic) === 10)//If NIC is 10 characters (e.g., 911234567V), it’s an old NIC format.
    {
        // Old NIC format
        $year = '19' . substr($nic, 0, 2);//First 2 digits → year: '91' → '1991'
        $dayText = substr($nic, 2, 3);// Next 3 digits → day number in the year (e.g., 123 = 123rd day)
    } 
    elseif (strlen($nic) === 12) // If NIC is 12 digits (e.g., 199812345678), it’s a new format
    {
        // New NIC format
        $year = substr($nic, 0, 4);// First 4 digits = year directly (1998)
        $dayText = substr($nic, 4, 3); //Next 3 digits = day of year
    } 
    else 
    {
        return [null, null]; // Invalid NIC format//If the NIC doesn’t match either format, the function exits early and returns null values.
    }

    $dayOfYear = (int) $dayText;
    if ($dayOfYear > 500) // If the day number is greater than 500, it means female.
    {
        $gender = 'Female';// If the day number is greater than 500, it means female.
        $dayOfYear -= 500; // To get the correct birth day number, subtract 500 from it.
    } else 
    {
        $gender = 'Male';//
    }

    // Convert year and day of year into a date
    $birthdate = \Carbon\Carbon::createFromDate($year, 1, 1)->addDays($dayOfYear - 1)->toDateString();

    return [$gender, $birthdate];
    }


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',//this is the foreign_key
            'nic' => ['required', 'regex:/^(\d{9}[vVxX]|\d{12})$/', 'unique:users,nic']
        ]);

         //  Extract gender and birthdate from NIC
        [$gender, $birthdate] = $this->extractDetailsFromNIC($request->nic);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'lastname'=>$request->lastname,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,
            'password' => Hash::make($request->password),
            'role_id'=>$request->role_id,
            'nic' => $request->nic,
            'gender' => $gender,
            'birthdate' => $birthdate,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
