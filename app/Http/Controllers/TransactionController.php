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
use Mike42\Escpos\EscposImage;
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
        try {
            function justifyText($left, $right, $width = 32) {
                $space = $width - mb_strlen($left) - mb_strlen($right);
                return $left . str_repeat(' ', max(0, $space)) . $right . "\n";
            }
    
            $connector = new WindowsPrintConnector("POS-58"); 
        
            $printer = new Printer($connector);
    
            // Header
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_EMPHASIZED);
            $printer->text("TOKO SETIA\n");
    
            $printer->selectPrintMode();           
            $printer->text(wordwrap("JL. Bangsri Guyangan", 32, "\n", true) . "\n");
            $printer->text("TRX-239799234\n");
            $printer->text("--------------------------------\n");
    
            // Info kasir & customer
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Cashier: Budiono Sirergar\n");
            $printer->text("Customer: Eric Steer\n");
            $printer->text("--------------------------------\n");
    
            // Header Produk
            $printer->text(justifyText("Nama Produk", "Total Harga"));
            $printer->text("--------------------------------\n");
            
            // Daftar Produk
            $items = [
                ["Buku Tulis Sidu 300 Lmbr", "1 PCS x 7000", "7000"],
                ["Pensil Kenko", "2 PCS x 2000", "4000"],
                ["Penghapus Faber Castel", "1 PCS x 5000", "5000"]
            ];
    
            foreach ($items as $item) {
                $printer->text(wordwrap($item[0], 32, "\n", true) . "\n"); // Nama produk wrap
                $printer->text(justifyText($item[1], $item[2])); // Jumlah x Harga
            }
    
            $printer->text("--------------------------------\n");
            $printer->text(justifyText("Subtotal", "16.000"));
            $printer->text(justifyText("Discount", "1.000"));
            $printer->text("--------------------------------\n");
            $printer->text(justifyText("Total", "15.000"));
            $printer->text("--------------------------------\n");
    
            // Waktu Transaksi
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text(date("d/m/Y H:i") . "\n");
            $printer->feed(2);
    
            // Cetak Barcode
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->feed(2);
            $printer->barcode("012345678905", Printer::BARCODE_UPCA);
            $printer->feed(2);
    
            // Selesai
            $printer->cut();
            $printer->close();
    
            return response()->json(["message" => "Receipt printed successfully"]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    

    public function store(Request $request){
        $length = 8;
        $random = Str::upper(Str::random($length)); 
        $date = date('Ymd'); 

        $invoice = "INV-$date-$random";

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
