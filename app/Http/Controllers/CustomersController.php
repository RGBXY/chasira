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
        $customers = Customers::latest()->paginate(12);

        return Inertia::render('Customers/index', [
            'customers' => $customers
        ]);
    }

    public function searchCustomerName(Request $request)
    {
        $customer = Customers::where('name', 'like', '%' . $request->name . '%')
                    ->paginate(12);

        return Inertia::render('Customers/index', [
            'customers' => $customer
        ]);
    }

    public function searchCustomerByPhone(Request $request)
    {
        $customer = Customers::where('phone', 'like', '%' . $request->phone . '%')
                    ->limit(12)  
                    ->get();       

        if ($customer->count() > 0) {
            return response()->json([
                'success' => true,
                'data'    => $customer
            ]);
        }

        return response()->json([
            'success' => false,
            'data'    => []
        ]);
    }

    public function create(){
        return Inertia::render('Customers/Create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'unique:customers,name,'],
            'address' => ['required'],
            'phone' => ['required', 'unique:customers,phone,'],
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
            'name' => ['required', 'unique:customers,nsame,' .$customer->id],
            'address' => ['required'],
            'phone' => ['required', 'unique:customers,phone,' .$customer->id],
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
