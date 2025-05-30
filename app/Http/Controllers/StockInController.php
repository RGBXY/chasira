<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOpname;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function Laravel\Prompts\select;

class StockInController extends Controller
{
    public function index()
    {
        $stockIn = StockIn::with(['product:id,name,barcode'])->with(['supplier:id,name'])
            ->latest()
            ->paginate(20);
        
        return Inertia::render('Stock_in/index', [
            'stockIn' => $stockIn,
        ]);
    }

    public function filterDate(Request $request)
    {
        $stockIn = StockIn::with('product:id,name,barcode')->with('supplier:id,name')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->latest()
            ->paginate(20);

        return Inertia::render('Stock_in/index', [
            'stockIn' => $stockIn,
        ]);
    }    

    public function searchByName(Request $request)
    {
        $stockIn = StockIn::with(['product:id,name,barcode'])
            ->whereHas('product', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->paginate(2);

        return Inertia::render('Stock_in/index', [
            'stockIn' => $stockIn,
        ]);
    }

    public function create(){
        $suppliers = Supplier::select(['id','name'])->limit(12)->get();
        $products =  Product::select(['id', 'name', 'stock'])->limit(12)->get();

        return Inertia::render('Stock_in/Create', [
            'suppliers' => $suppliers,
            'products'  => $products
        ]);
    }

    public function store(Request $request){

        $request->validate([
            'qty' => ['required'],
            'detail' => ['required'],
            'product_id' => ['required'],
            'supplier_id' => ['required']
        ]);

        $product = Product::find($request->product_id);

        $product->stock += $request->qty;
        $product->save();

        StockIn::create([
            'qty' => $request->qty,
            'detail' => $request->detail,
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id
        ]);

        return redirect('/stock-in')->with('message', 'Stock Added Successfully');
    }
}
