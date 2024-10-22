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
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'lastname'=>$request->lastname,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,
            'password' => Hash::make($request->password),
            'role_id'=>$request->role_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
