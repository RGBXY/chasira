<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOpname;
use App\Models\StockOut;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockOutController extends Controller
{
    public function index(){
        $StockOut = StockOut::with('product')
        ->whereHas('product', function ($query) {
            if (request()->search) {
                $query->where('name', 'like', '%' . request()->search . '%');
            }
        })
        ->latest()
        ->paginate(20);

        $hasStockIn = StockIn::exists();

         return Inertia::render('Stock_out/index', [
        'StockOut' => $StockOut,
        'hasStockIn' => $hasStockIn
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

    public function filter(Request $request)
    {
        $request->validate([
            'start_date'  => 'required',
            'end_date'    => 'required',
        ]);

        //get data sales by range date
        $StockOut = StockOut::with('product')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->latest()
            ->paginate(20);

        return Inertia::render('Stock_out/index', [
            'StockOut' => $StockOut,
        ]);
    }    

    public function create(){
        return Inertia::render('Stock_out/Create');
    }

    public function store(Request $request){
        $request->validate([
            'display_stock' => ['required'],
            'opname_stock' => ['required'],
            'detail' => ['required'],
            'product_id' => ['required'],
        ]);

        $product = Product::find($request->product_id);
        $stockOpname = StockOpname::where('product_id', $request->product_id)->first();

        if ($product) {
            $product->stock -= $request->display_stock;

            if ($product->stock <= 0) {
                $product->stock = 0;
                $product->save();
            } else {
                $product->save();
            }
        }

        if ($stockOpname) {
            $stockOpname->qty -= $request->opname_stock;

            if ($stockOpname->qty <= 0) {
                $stockOpname->stock = 0;
                $stockOpname->save();
            } else {
                $stockOpname->save();
            }
        }

        StockOut::create([
            'display_stock' => $product->stock == 0 ? 0 : $request->display_stock,
            'opname_stock' => $stockOpname == null || $stockOpname->qty == 0 ? 0 : $request->opname_stock,
            'detail' => $request->detail,
            'product_id' => $request->product_id,
        ]);

        return redirect('/stock-out')->with('message', 'Stock Added Successfully');
    }

    public function destroy($id)
    {   
        $StockOut = StockOut::findOrFail($id);
        $product = Product::findOrFail($StockOut->product_id);
        $stockOpname = StockOpname::where('product_id', $StockOut->product_id)->first();

        if ($product && $product->stock !== 0) {
            $product->stock += $StockOut->display_stock;

            if ($product->stock < 0) {
                $product->stock = 0;
                $product->save();
            } else {
                $product->save();
            }
        }

        if ($stockOpname && $product->stock !== 0) {
            $stockOpname->qty += $StockOut->opname_stock;

            if ($stockOpname->qty < 0) {
                $stockOpname->qty = 0;
                $stockOpname->save();
            } else {
                $stockOpname->save();
            }
        }

        // Hapus Sock$StockOut
        $StockOut->delete();

        return redirect('/stock-out')->with('message', 'Product Deleted Successfully');
    }

    
}
