<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index(){
        $user = User::where('family_id', Auth::user()->family_id)
        ->where('parent_id', Auth::user()->family_id)

        ->when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%'); 
        })
        ->with('outlet')->with('role')
        ->latest()->get();

        return Inertia::render("Employees/index", [
            'user' => $user
        ]);
    }

    public function create(){
        $outlets = Outlet::where('family_id', Auth::user()->family_id)->where('status', 'active')->get();
        $roles = Role::where('family_id', Auth::user()->family_id)->get();

        return Inertia::render("Employees/Create", [
            'outlets' => $outlets,
            'roles' => $roles
        ]);
    }

    public function store(Request $request){

        $request->validate([
            'name' => ['required', 'max:225'],
            'email' => ['required', 'email', 'max:225', 'unique:users,email'],
            'password' => ['required'],
            'role_id' => ['required'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'outlet_id' => $request->outlet_id,
            'status' => $request->status,
            'parent_id' => Auth::user()->family_id,
            'family_id' => Auth::user()->family_id,
            'role_id' => $request->role_id
        ]);

        $user->assignRole($request->role_id);

        return redirect('/employees')->with('message', 'New Employee Added Successfully');
    }

    public function edit($id){
        $employee = User::findOrFail($id);
        $outlets = Outlet::where('user_id', getUserIdForQuery())->where('status', 'active')->get();
        $roles = Role::where('created_by', getUserIdForQuery())->get();

        return Inertia::render('Employees/Edit', [
            'user' => $employee,
            'outlets' => $outlets,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $employee){
        $request->validate([
            'name' => ['required', 'max:225'],
            'email' => ['required', 'email', 'max:225', Rule::unique('users')->ignore($employee->id)],
            'role_id' => ['required'],
        ]);

         $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'outlet_id' => $request->outlet_id,
            'status' => $request->status,
            'role_id' => $request->role_id
        ]);

        return redirect("/employees")->with('message', 'Employee Edited Successfully');
    }

    public function destroy($id){
        $employee = User::findOrFail($id);

        $employee->delete()->with('message', 'Employee Deleted Successfully');
    }

    public function deactivate($id){
        $employee = User::findOrFail($id);

        $employee->update(['status' => 'inactive']);

    }

    public function activate($id){
        $employee = User::findOrFail($id);

        $employee->update(['status' => 'active']);
    }
}
