<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MainUserRegister;
use App\Models\Role;

use App\Models\Veterinarian;
use App\Models\Retailer;
use App\Models\SalesManager;
use App\Models\GeneralManager;
use App\Models\FarmLabore;

class MainUserRegisterController extends Controller
{
    //

    public function index()
    {
       $users =User::all();

       return view('main_user_details.index',['users'=>$users]);
    }


    public function view(User $user)
    {
          $targeted_role_ids = ['1','2','3','5','6','7'];

        $roles= Role::whereIn('id',$targeted_role_ids)->get();

       return view('main_user_details.view',['user'=>$user,'roles'=>$roles]);
    }


    public function edit(User $user)
    {
        $targeted_role_ids = ['1','2','3','5','6','7'];

        $roles= Role::whereIn('id',$targeted_role_ids)->get();

 

       return view('main_user_details.edit',['user'=>$user,'roles'=>$roles]);
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

    public function update(request $request,User $user)
    {
         $user = User::findOrFail($user->id);//find the user whose record is  being updated

     

         $oldRoleId = $user->role_id;// find the user's role_id

          $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role_id' => 'required|exists:roles,id',
            'lastname' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'nic' => ['required', 'regex:/^(\d{9}[vVxX]|\d{12})$/', 'unique:users,nic,' . $user->id]
        ]);

            //  Extract gender and birthdate from NIC
        [$gender, $birthdate] = $this->extractDetailsFromNIC($request->nic);


        // 4. Handle role change
        if ($validated['role_id'] != $oldRoleId) 
        {
                switch ($oldRoleId) 
                {
                         case 2: // veterinarian
                                Veterinarian::where('veterinarian_id', $user->id)->delete();
                                break;
                        case 7: // sales manager
                                SalesManager::where('sales_manager_id', $user->id)->delete();
                                break;
                        case 5: // labore
                                FarmLabore::where('farm_labore_id', $user->id)->delete();
                                break;

                         case 6: // labore
                                GeneralManager::where('general_manager_id', $user->id)->delete();
                                break;

                        case 3:
                                Retailer::where('retailer_id', $user->id)->delete();
                                break;

                    
                        
           
                }
        }
 
     
        // 5. Update user
        $user->update($validated);


      
         return redirect()->route('main_user_details.list')->with('success', 'User Details updated successfully!');

    }

}
