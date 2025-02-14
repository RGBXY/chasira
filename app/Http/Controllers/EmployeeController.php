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
        ->latest()->paginate(20);

        return Inertia::render("Employees/index", [
            'user' => $user
        ]);
    }

    public function create(){
        $roles = Role::where('family_id', Auth::user()->family_id)->get();

        return Inertia::render("Employees/Create", [
            'roles' => $roles
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
            'outlet_id' => $request->outlet_id,
            'status' => $request->status,
        ]);

        $user->assignRole($request->role_id);

        return redirect('/employees')->with('message', 'New Employee Added Successfully');
    }

    public function edit($id){
        $employee = User::findOrFail($id);
        $roles = Role::where('created_by', getUserIdForQuery())->get();

        return Inertia::render('Employees/Edit', [
            'user' => $employee,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $employee){
        $request->validate([
            'name' => [
                'required',
                'max:225',
                Rule::unique('users')
                ->where('family_id', Auth::user()->family_id)
                ->ignore($employee->id)
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

    public function deactivate($id){
        $employee = User::findOrFail($id);

        $employee->update(['status' => 'inactive']);

    }

    public function activate($id){
        $employee = User::findOrFail($id);

        $employee->update(['status' => 'active']);
    }
}
