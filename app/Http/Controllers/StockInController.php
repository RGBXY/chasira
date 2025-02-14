<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockInController extends Controller
{
    public function index(){
        $suppliers = Supplier::when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
        })
        ->latest()
        ->paginate(20);

         return Inertia::render('Stock_in/index', [
        'suppliers' => $suppliers,
    ]); 
    }

    public function create(){

        $products = Product::latest()->get();

        return Inertia::render('Stock_in/Create', [
            'products' => $products
        ]);
    }


}
