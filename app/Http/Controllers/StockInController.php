<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOpname;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockInController extends Controller
{
    public function index()
    {
        $stockIn = StockIn::with('product')->with('supplier')
            ->whereHas('product', function ($query) {
                if (request()->search) {
                    $query->where('name', 'like', '%' . request()->search . '%');
                }
            })
            ->latest()
            ->paginate(20);
    
        $stockOpname = StockOpname::latest()->get();
    
        return Inertia::render('Stock_in/index', [
            'stockIn' => $stockIn,
            'stock_opname' => $stockOpname
        ]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start_date'  => 'required',
            'end_date'    => 'required',
        ]);

        //get data sales by range date
        $stockIn = StockIn::with('product')->with('supplier')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->latest()
            ->paginate(20);

        return Inertia::render('Stock_in/index', [
            'stockIn' => $stockIn,
        ]);
    }    

    public function searchProduct(Request $request)
    {
        // Cek apakah barcode atau name yang dikirim
        $product = Product::with('stock_opname')->where('barcode', $request->barcode)->get();

        if ($product) {
            return response()->json([
                'success' => true,
                'data'    => $product
            ]);
        }    

        return response()->json([
            'success' => false,
            'data'    => null
        ]);
    }

    public function create(){
        $products = Product::latest()->get();
        $suppliers = Supplier::latest()->get();

        return Inertia::render('Stock_in/Create', [
            'products' => $products,
            'suppliers' => $suppliers
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'display_stock' => ['required'],
            'opname_stock' => ['required'],
            'detail' => ['required'],
            'product_id' => ['required'],
            'supplier_id' => ['required']
        ]);

        $product = Product::find($request->product_id);
        $stockOpname = StockOpname::where('product_id', $request->product_id)->first();

        $product->stock += $request->display_stock;
        $product->save();

        if ($stockOpname) {
            $stockOpname->qty += $request->opname_stock;
            $stockOpname->save();
        } else {
            StockOpname::create([
                'product_id' => $request->product_id,
                'qty' => $request->opname_stock
            ]);
        }

        StockIn::create([
            'display_stock' => $request->display_stock,
            'opname_stock' => $request->opname_stock,
            'detail' => $request->detail,
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id
        ]);

        return redirect('/stock-in')->with('message', 'Stock Added Successfully');
    }

    public function destroy($id)
    {   
        $stockIn = StockIn::findOrFail($id);
        $product = Product::findOrFail($stockIn->product_id);
        $stockOpname = StockOpname::where('product_id', $stockIn->product_id)->first();
        
        if ($product) {
            $product->stock -= $stockIn->display_stock;

            if ($product->stock < 0) {
                $product->stock = 0;
                $product->save();
            } else {
                $product->save();
            }
        }

        if ($stockOpname) {
            $stockOpname->qty -= $stockIn->opname_stock;

            if ($stockOpname->qty <= 0) {
                $stockOpname->delete();
            } else {
                $stockOpname->save();
            }
        }

        // Hapus StockIn
        $stockIn->delete();

        return redirect('/stock-in')->with('message', 'Product Deleted Successfully');
    }

    




}
