<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    
    public function index()
    {
        $roles = Role::with('permissions') 
            ->select(['id', 'name'])
            ->latest()
            ->paginate(12);

        return Inertia::render("Roles/index", [
            'roles' => $roles
        ]);
    }

    public function dropDownRole(Request $request)
    {
        $role = Role::where('name', 'like', '%' . $request->name . '%')
        ->select(['id', 'name'])
        ->limit(12)  
        ->get();             

        if ($role) {
            return response()->json([
                'success' => true,
                'data'    => $role
            ]);
        }    

        return response()->json([
            'success' => false,
            'data'    => null
        ]);
    }

    public function searchRoles(Request $request)
    {
        // Cek apakah barcode atau name yang dikirim
        $roles = Role::where('name', 'like', '%' . $request->name . '%')
        ->with('permissions') 
        ->select(['id', 'name'])
        ->latest()
        ->paginate(12);             

        return Inertia::render("Roles/index", [
            'roles' => $roles
        ]);
    }
    
    public function create(){
        $permissions = Permission::all();

        return Inertia::render("Roles/Create", [
            "permissions" => $permissions
        ]);
    }

    public function store(Request $request){
        $request->validate([
            "name" => ["required", "max:225", Rule::unique('roles')],
            "permissions" => ["required"]
        ]);

        $role = Role::create([
            'name' => $request->name,
            'created_by' => Auth::id(),
        ]);

        $role->givePermissionTo($request->permissions);

        return redirect("/roles")->with('message', 'Role Created Succesfully');;
    }

    public function edit($id){

        $role = Role::with('permissions')->findOrFail($id);

        $permissions = Permission::all();

        return Inertia::render("Roles/Edit", [
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    public function update(Request $request, Role $role){
        $request->validate([
            'name' => [
                'required',
                'max:225',
                Rule::unique('roles')
                ->ignore($role->id)
            ],
            "permissions" => ["required"]
        ]);

        $role->update(['name' => $request->name]);

        $role->syncPermissions($request->permissions);

        return redirect('/roles')->with('message', 'Role Edited Succesfully');;
    }

    // public function destroy($id)
    // {
    //     //find role by ID
    //     $role = Role::findOrFail($id);

    //     //delete role
    //     $role->delete();

    //     //redirect
    //     return redirect("/roles");
    // }
}
