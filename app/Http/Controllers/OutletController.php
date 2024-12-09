<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OutletController extends Controller
{
    public function index(){
        $outlets = Outlet::where('family_id', Auth::user()->family_id)
        ->when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
        })->when(request()->status, function ($query) {
            $query->where('status', request()->status);
        })->latest()->get();

        return Inertia::render('Outlets/index', [
            'outlets' => $outlets
        ]);
    }

    public function create(){
        return Inertia::render('Outlets/Create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'max:225', 'unique:outlets,name,'],
            'address' => ['required', 'max:225'],
            'city' => ['required', 'max:225'],
            'phone' => ['required', 'max:225'],
            'email' => ['required', 'max:225'],
            'description' => ['required', 'max:225']
        ]);

        Outlet::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'phone' => $request->phone,
            'email' => $request->email,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => Auth::id(),
            'family_id' => Auth::user()->family_id
        ]);

        return redirect('/outlets');
    }

    public function edit(Outlet $outlet){
        return Inertia::render('Outlets/Edit', [
            'outlet' => $outlet
        ]);
    }

    public function update(Request $request, Outlet $outlet){
        $request->validate([
            'name' => ['required', 'max:225', 'unique:outlets,name,'.$outlet->id],
            'address' => ['required', 'max:225'],
            'city' => ['required', 'max:225'],
            'phone' => ['required', 'max:225'],
            'email' => ['required', 'max:225'],
            'description' => ['required', 'max:225']
        ]);

        $outlet->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'phone' => $request->phone,
            'email' => $request->email,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => Auth::id()
        ]);

        return redirect('/outlets');

    }

    public function destroy($id){
        $outlet = Outlet::findOrFail($id);

        $outlet->delete();
    }

    public function activate($id){
        $outlet = Outlet::findOrFail($id);

        $outlet->update(['status' => 'active']);
    }

    public function deactivate($id){
        $outlet = Outlet::findOrFail($id);

        $outlet->update(['status' => 'inactive']);
    }

    
}
