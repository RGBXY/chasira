<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    
    public function index()
    {
        $roles = Role::where('created_by', Auth::id()) 
            ->with('permissions') 
            ->when(request()->search, function ($query) {
                $query->where('name', 'like', '%' . request()->search . '%'); 
            })
            ->latest()
            ->get();

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
            "name" => ["required", "max:225", "unique:roles,name,"],
            "permissions" => ["required"]
        ]);

        $role = Role::create([
            'name' => $request->name,
            'created_by' => Auth::id()
        ]);

        $role->givePermissionTo($request->permissions);

        return redirect("/roles");
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
            "name" => ["required", "max:225", "unique:roles,name,".$role->id],
            "permissions" => ["required"]
        ]);

        $role->update(['name' => $request->name]);

        $role->syncPermissions($request->permissions);

        return redirect('/roles');
    }

    public function destroy($id)
    {
        //find role by ID
        $role = Role::findOrFail($id);

        //delete role
        $role->delete();

        //redirect
        return redirect("/roles");
    }
}
