<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index(){
        $user = User::with('roles:id,name')->latest()
        ->where('id', '!=', 1)
        ->paginate(12);

        return Inertia::render("Employees/index", [
            'user' => $user
        ]);
    }

    public function create(){
        $roles = Role::with('permissions') 
        ->select(['id', 'name'])
        ->latest()
        ->limit(12)
        ->get();


        return Inertia::render("Employees/Create", [
            'roles' => $roles
        ]);
    }

    public function searchEmployeesName(Request $request)
    {
        $employee = User::where('name', 'like', '%' . $request->name . '%')
                    ->where('id', '!=', 1)
                    ->with('roles:id,name')
                    ->paginate(12);     

        return Inertia::render("Employees/index", [
            'user' => $employee
        ]);
    }

    public function store(Request $request){

        $request->validate([
            'name' => ['required', 'max:225', Rule::unique('users')],
            'email' => ['required', 'email', 'max:225', 'unique:users,email,'],
            'password' => ['required'],
            'role_id' => ['required'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status,
        ]);

        $user->assignRole($request->role_id);

        return redirect('/employees')->with('message', 'New Employee Added Successfully');
    }

    public function edit($id){
        $employee = User::findOrFail($id);
        $roles = Role::latest()->get();

        return Inertia::render('Employees/Edit', [
            'user' => $employee,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $employee){
        $request->validate([
            'name' => [
                'required', Rule::unique('users')->ignore($employee->id)
            ],
            'email' => ['required', 'email', 'max:225', 'unique:users,email,'.$employee->id],
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

        $employee->syncRoles($request->role_id);

        return redirect("/employees")->with('message', 'Employee Edited Successfully');
    }

    public function destroy($id){
        $employee = User::findOrFail($id);

        $employee->delete();

        return redirect()->back()->with('message', 'Employee Deleted Successfully');
    }

    public function deactivate($id)
    {
        $employee = User::findOrFail($id);
        $employee->update(['status' => 'inactive']);
    }

    public function activate($id){
        $employee = User::findOrFail($id);
        $employee->update(['status' => 'active']);
    }
}
