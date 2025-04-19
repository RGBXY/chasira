<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOpname;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockOutController extends Controller
{
    public function index(){
        $StockOut = StockOut::with(['product:id,name,stock,barcode'])
        ->latest()
        ->paginate(12);

         return Inertia::render('Stock_out/index', [
            'stockOut' => $StockOut,
        ]); 
    }

    public function filterDate(Request $request)
    {
        $stockOut = StockOut::with(['product:id,stock,name,barcode'])
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->latest()
            ->paginate(12);

        return Inertia::render('Stock_out/index', [
                'stockOut' => $stockOut,
        ]); 
    }    

    public function searchByName(Request $request)
    {
        $stockOut = StockOut::with(['product:id,name,barcode'])
            ->whereHas('product', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->paginate(12);
            
        return Inertia::render('Stock_out/index', [
            'stockOut' => $stockOut,
        ]);     
    }

    public function create(){
        $products = Product::select(['id','name','stock'])->limit(3)->get();

        return Inertia::render('Stock_out/Create', [
            'products' => $products
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'qty' => ['required'],
            'detail' => ['required'],
            'product_id' => ['required'],
        ]);

        $product = Product::find($request->product_id);

        if ($product) {
            $product->stock -= $request->qty;

            if ($product->stock <= 0) {
                $product->stock = 0;
                $product->save();
            } else {
                $product->save();
            }
        }

        StockOut::create([
            'qty' => $product->stock == 0 ? 0 : $request->qty,
            'detail' => $request->detail,
            'product_id' => $request->product_id,
        ]);

        return redirect('/stock-out')->with('message', 'Stock Added Successfully');
    }

    

    
}
