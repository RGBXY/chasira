<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class TransactionController extends Controller
{
    public function index()
{    
    $products = Product::with(['category:id,name'])
        ->when(request()->category_id, function ($query) {
            $query->where('category_id', request()->category_id);
        })
        ->orderBy('stock', 'desc')
        ->paginate(10);
    
    $categories = Category::withCount('products')
        ->select('id', 'name')
        ->limit(5)
        ->get();

    $customers = Customers::select('id', 'name')
        ->limit(5)
        ->get();

    return Inertia::render('Transactions/index', [
        'products'      => $products,
        'categories'    => $categories,
        'customers'     => $customers,
    ]); 
}

    public function searchByName(Request $request)
    {
        $products = Product::where('name', 'like', '%' . $request->name . '%')
                    ->with('category:id,name')
                    ->limit(12)  
                    ->get();       

        if ($products->count() > 0) {
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

    public function searchByBarcode(Request $request)
    {
        $product = Product::where('barcode', $request->barcode)->first();        

        if ($product) {
            return response()->json([
                'success' => true,
                'data'    => $product
            ]);
        }

        return response()->json([
            'success' => false,
            'data'    => null,
        ]);
    }

    public function destroyCart(){
        Cart::where('user_id', Auth::id())->delete();
    }

    public function cart(Request $request){
        $this->destroyCart();

        $items = $request->all();

        foreach ($items['products'] as $item) {
            // Cek stok produk
            $product = Product::findOrFail($item['id']);
            if ($product->stock < $item['total']) {
                return redirect()->back()->with('error', "Product {$product->name} is out of stock!");
            };
    
            // Masukkan ke tabel Cart
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $item['id'],
                'qty'        => $item['total'],
                'price'      => $product->sell_price * $item['total'],
            ]);
        }

    }

    public function printReceipt(){
        $connector = new WindowsPrintConnector("POS-58");

        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Toko Setia\n");
        $printer->feed();

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Pensil : 5000\n");
        $printer->text("Buku : 6000\n");

        $printer->cut();
        $printer->close();
    }

    public function store(Request $request){

        $length = 10;
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
        }

        $invoice = 'TRX-'.Str::upper($random);

        $request->validate([
            'cash'          => ['required'],
            'customer_id'   => ['required']
        ]);

        $discount = $request->discount;

        if($discount === null){
            $discount = 0;
        }

        $transaction = Transaction::create([
            'user_id'       => Auth::id(),
            'invoice'       => $invoice,
            'total'         => $request->total,
            'cash'          => $request->cash,
            'change'        => $request->change,
            'customer_id'   => $request->customer_id,
            'discount'      => $discount,
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
