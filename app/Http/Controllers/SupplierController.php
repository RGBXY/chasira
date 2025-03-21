<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function index(){
        $suppliers = Supplier::latest()->paginate(10);
         return Inertia::render('Suppliers/index', [
        'suppliers' => $suppliers,
    ]); 
    }

    public function searchSupplierName(Request $request)
    {
        $supplier = Supplier::where('name', 'like', '%' . $request->name . '%')
                    ->limit(12)  
                    ->get();       

        if ($supplier->count() > 0) {
            return response()->json([
                'success' => true,
                'data'    => $supplier
            ]);
        }

        return response()->json([
            'success' => false,
            'data'    => []
        ]);
    }

    public function create(){
        return Inertia::render('Suppliers/Create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'max:225', 'unique:suppliers,name'],
            'address' => ['required'],
            'phone' => ['required'],
            'description' => ['required', 'max:225'],
        ]);

        Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'description' => $request->description,
        ]);

        return redirect('/suppliers')->with('message', 'Data Suppliers Added Successfully');
    }

    public function edit(Supplier $supplier){
        return Inertia::render('Suppliers/Edit', [
            'supplier' => $supplier
        ]);
    }

    public function update(Request $request, Supplier $supplier){
        $request->validate([
            'name' => ['required', 'max:225', 'unique:suppliers,name,' .$supplier->id],
            'address' => ['required'],
            'phone' => ['required'],
            'description' => ['required', 'max:225'],
        ]);

        $supplier->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'description' => $request->description,
        ]);

        return redirect('/suppliers')->with('message', 'Suppliers Data Updated Succesfully');
    }

    public function destroy($id){
        $supplier = Supplier::findOrFail($id);

        $supplier->delete();

        return redirect('/suppliers')->with('message', 'Suppliers Data Deleted Succesfully');
    }
}

