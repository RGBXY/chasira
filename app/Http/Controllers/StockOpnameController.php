<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockOpnameController extends Controller
{
    public function index(){
        $stock_opname = Product::with('stock_opname')
        ->when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
        })
        ->latest()
        ->paginate(20);

         return Inertia::render('Stock_opname/index', [
            'stock_opname' => $stock_opname,
        ]); 
    }

    public function transfer(){
        
    }

}
