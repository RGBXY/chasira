<?php

namespace App\Http\Controllers;

use App\Models\User;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request){
        // Vallidate
        $request->validate([
            'name' => ['required', 'max:225'],
            'email' => ['required', 'email', 'max:225', 'unique:users'],
            'password' => ['required'],
        ]);

        // Register
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $user->update(['family_id' => $user->id]);

        $permissions = Permission::all();

        $role = Role::find(1);

        $role->syncPermissions($permissions);

        $user->assignRole($role);

        // Login
        Auth::login($user);

        // Redirect
        return redirect("/")->with('message', 'Register Success');
        
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(Auth::attempt($fields, $request->remember)){
            $request->session()->regenerate();
            return redirect()->intended('/')->with('message', 'Login Success');
        };

        return back()->withErrors([
            'email' => 'Email Tidak di Kenali'
        ])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('message', 'Logout Success') ;
    }
}
