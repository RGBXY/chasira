<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockOpnameController extends Controller
{
    public function index(){
        $stockOpname = StockOpname::with(['product:id,name,stock'])
        ->latest()
        ->paginate(12);

         return Inertia::render('Stock_opname/index', [
            'stockOpname' => $stockOpname,
        ]); 
    }

    public function create(){
        $products = Product::select(['id','name','stock'])->limit(3)->get();

        return Inertia::render('Stock_opname/Create', [
            'products' => $products
        ]);
    }

    public function searchByName(Request $request)
    {
        $stockOpname = StockOpname::with(['product:id,name,barcode'])
            ->whereHas('product', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->paginate(12);

        return Inertia::render('Stock_opname/index', [
            'stockOpname' => $stockOpname,
        ]); 
    }

    public function filterDate(Request $request)
    {
        $stockOpname = StockOpname::with('product:id,name,barcode')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->latest()
            ->paginate(12);

        return Inertia::render('Stock_opname/index', [
            'stockOpname' => $stockOpname,
        ]); 
    }    

    public function store(Request $request){
        $request->validate([
            'product_id' => ['required'],
            'actual_stock' => ['required'],
            'system_stock' => ['required'],
            'description' => ['required'],
        ]);

        $product = Product::find($request->product_id);
        
        $product->stock = $request->actual_stock;
        $product->save();

        $discrepancy = $request->actual_stock - $request->system_stock;

        StockOpname::create([
            'product_id' => $request->product_id,
            'actual_stock' => $request->actual_stock,
            'system_stock' => $request->system_stock,
            'discrepancy_stock' => $discrepancy,
            'description' => $request->description,
        ]);

        return redirect('/stock-opname')->with('message', 'Stock Opname Added Successfully');
    }







}
