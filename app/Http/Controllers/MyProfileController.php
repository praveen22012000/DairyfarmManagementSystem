<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MyProfileController extends Controller
{
    //
    public function show()//this function is used display profile
    {
        $user = Auth::user(); // logged-in user
        return view('my_profile.show', compact('user'));
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
        } 

        else 
        {
        $gender = 'Male';//
        }

         // Convert year and day of year into a date
        $birthdate = \Carbon\Carbon::createFromDate($year, 1, 1)->addDays($dayOfYear - 1)->toDateString();

        return [$gender, $birthdate];
    }


    public function edit()//this function is used to edit the profile to display form
    {
        $user = Auth::user();
        return view('my_profile.edit', compact('user'));
    }

    public function update(Request $request)//this function update the profile
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'nic' => ['required', 'regex:/^(\d{9}[vVxX]|\d{12})$/', 'unique:users,nic,' . $user->id],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'gender' => 'nullable|string',
            'birthdate' => 'nullable|date',
         
        ]);

             //  Extract gender and birthdate from NIC
        [$gender, $birthdate] = $this->extractDetailsFromNIC($request->nic);

        // Merge extracted values manually
        $validated['gender'] = $gender;
        $validated['birthdate'] = $birthdate;

        $user->update($validated);

        return redirect()->route('my_profile.show')->with('success', 'Profile updated successfully.');
    }

    public function showChangePasswordForm()//this function is used to display the password change form
    {
        return view('my_profile.change_password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) 
        {
        throw ValidationException::withMessages([
            'current_password' => 'Current password is incorrect.',
        ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('my_profile.show')->with('success', 'Password changed successfully.');
    }
}
