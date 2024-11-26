<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(){
        $products = Product::where('user_id', Auth::id())
        ->with('category')

        ->when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
        })
        ->when(request()->category_id, function ($query) {
            $query->where('category_id', request()->category_id);
        })
        ->latest()
        ->get();

        $categories = Category::where('user_id', Auth::id())
        ->withCount('products') 
        ->latest()
        ->get();

         return Inertia::render('Transactions/index', [
        'products' => $products,
        'categories' => $categories, // Opsional, untuk dropdown kategori
        ]); 
    }

    public function store(Request $request){

        $length = 10;
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
        }

        $invoice = 'TRX-'.Str::upper($random);

        $transaction = Transaction::create([
            'chasier_id' => Auth::id(),
            'invoice'       => $invoice,
            'cash'          => $request->cash,
            'change'        => $request->change,
            'discount'      => $request->discount,
            'grand_total'   => $request->grand_total,
        ]);
    } 
}
