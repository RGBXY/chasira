<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index(){
        $user = User::where('user_id', Auth::id())->latest()->get();

        return Inertia::render("Employees/index", [
            'user' => $user
        ]);
    }

    public function create(){
        $outlets = Outlet::where('user_id', Auth::id())->get();
        $roles = Role::where('created_by', Auth::id())->get();

        return Inertia::render("Employees/Create", [
            'outlets' => $outlets,
            'roles' => $roles
        ]);
    }

    public function store(Request $request){

        $request->validate([
            'name' => ['required', 'max:225'],
            'email' => ['required', 'email', 'max:225', 'unique:users'],
            'password' => ['required'],
            'asigned_outlet' => ['required'],
            'role' => ['required'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'asigned_outlet' => $request->asigned_outlet,
            'status' => $request->status,
            'user_id' => Auth::id(),
            'role' => $request->role
        ]);

        $user->assignRole($request->roles);

        return redirect('/employees');
    }
}
