<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {    
        $products = Product::with('category')
            ->when(request()->search, function ($query) {
                $query->where('name', 'like', '%' . request()->search . '%');
            })
            ->when(request()->category_id, function ($query) {
                $query->where('category_id', request()->category_id);
            })
            ->latest()
            ->paginate(4);
    
        $categories = Category::withCount('products')
            ->limit(5)
            ->get();

        $customers = Customers::latest()->get();

        // Return Inertia view
        return Inertia::render('Transactions/index', [
            'products' => $products,
            'categories' => $categories,
            'customers' => $customers,
        ]); 
    }

    public function searchProduct(Request $request)
    {
        // Cek apakah barcode atau name yang dikirim
        $query = Product::with('category');

        if ($request->barcode) {
            $query->where('barcode', $request->barcode);
        } elseif ($request->name) {
            // Cari yang namanya mengandung keyword
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $products = $query->get();

        if ($products->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'data'    => $products
            ]);
        }

        return response()->json([
            'success' => false,
            'data'    => []
        ]);
    }

    public function cart(Request $request){
        $items = $request->all();

        foreach ($items['products'] as $item) {
            // Cek stok produk
            
            $product = Product::findOrFail($item['id']);
            if ($product->stock < $item['total']) {
                return redirect()->back()->with('error', "Product {$product->name} is out of stock!");
            }
    
            // Masukkan ke tabel Cart
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $item['id'],
                'qty'        => $item['total'],
                'price'      => $product->sell_price * $item['total'],
            ]);
        }

    }

    public function destroyCart(){
        Cart::where('user_id', Auth::id())->delete();
        
        return redirect()->back()->with('success', 'Product Removed Successfully!.');
    }

    public function store(Request $request){

        $length = 10;
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
        }

        $invoice = 'TRX-'.Str::upper($random);

        $transaction = Transaction::create([
            'user_id'       => Auth::id(),
            'invoice'       => $invoice,
            'total'         => $request->total,
            'cash'          => $request->cash,
            'change'        => $request->change,
            'customer_id'   => $request->customer_id,
            'discount'      => $request->discount,
            'grand_total'   => $request->grand_total,
        ]);

        $carts = Cart::where('user_id', Auth::id())->get();

        foreach ($carts as $cart) {
            $transaction->details()->create([
                'transaction_id'    => $transaction->id,
                'product_id'        => $cart->product_id,
                'qty'               => $cart->qty,
                'price'             => $cart->price,
            ]);
            
            $total_buy_price = $cart->product->buy_price * $cart->qty;
            $total_sell_price = $cart->product->sell_price * $cart->qty;
            $discount = $cart->product->discount;

            $profits = $total_sell_price - $total_buy_price - $discount;

            $transaction->profits()->create([
                'transaction_id' => $transaction->id,
                'total' => $profits
            ]);

            $product = Product::find($cart->product_id);
            $product->stock = $product->stock - $cart->qty;
            $product->save();
        }

        Cart::where('user_id', Auth::id())->delete();
    } 
}
