<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customers;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomersController extends Controller
{
    public function index()
    {
        $customers = Customers::when(request()->search, function($customers) {
            $customers = $customers->where('name', 'like', '%'. request()->search . '%');
        })->latest()->paginate(20);

        return Inertia::render('Customers/index', [
            'customers' => $customers
        ]);
    }

    public function create(){
        return Inertia::render('Customers/Create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'unique:customers,name,'],
            'address' => ['required'],
            'phone' => ['required'],
            'gender' => ['required'],
            'description' => ['required', 'max:225']
        ]);

        Customers::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'description' => $request->description
        ]);

        return redirect('/customers')->with('message', 'Customers Data Added Successfully');
    }

    public function edit(Customers $customer){
        return Inertia::render('Customers/Edit', [
            'customer' => $customer
        ]);
    }

    public function update(Request $request, Customers $customer){
        $request->validate([
            'name' => ['required', 'unique:customers,name,' .$customer->id],
            'address' => ['required'],
            'phone' => ['required'],
            'gender' => ['required'],
            'description' => ['required', 'max:225']
        ]);

        $customer->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'description' => $request->description
        ]);

        return redirect('/customers')->with('message', 'Customers Data Edited Successfully');

    }

    public function destroy($id){
       $customer = Customers::findOrFail($id);

       $customer->delete();

       return redirect('customers')->with('message', 'Customers Data Deleted Successfully');
    }
}
