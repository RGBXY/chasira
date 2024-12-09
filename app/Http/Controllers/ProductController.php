<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(){
        $products = Product::where('family_id', Auth::user()->family_id)
        ->with('category')
        ->when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
        })
        ->when(request()->category_id, function ($query) {
            $query->where('category_id', request()->category_id);
        })
        ->latest()
        ->get();

         return Inertia::render('Products/index', [
        'products' => $products,
        'categories' => Category::all(), // Opsional, untuk dropdown kategori
    ]); 
    }

    public function create(){
        $categories = Category::where('family_id', Auth::user()->family_id)
        ->latest()->get();

        return Inertia::render('Products/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request){       
        $request->validate([
            'name' => ['required', 'max:225', 'unique:products,name,'],
            'buy_price' => ['required','numeric'],
            'sell_price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
            'description' => ['required', 'max:225'],
            'image' => ['required', 'file', 'nullable', 'max:300'],
            'category_id' => ['required']
        ]);

        if($request->hasFile('image')){
            $image = Storage::disk('public')->put('product-images', 
            $request->image);
        }

        Product::create([
            'name' => $request->name,
            'buy_price' => $request->buy_price,
            'sell_price' => $request->sell_price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $image,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'family_id' => Auth::user()->family_id
        ]);

        return redirect('/products');
    }

    public function edit(Product $product){
        $categories = Category::where('family_id', Auth::user()->family_id)
        ->latest()->get();

        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Product $product){       

        $request->validate([
            'name' => ['required', 'max:225', 'unique:products,name,'.$product->id],
            'buy_price' => ['required','numeric'],
            'sell_price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
            'description' => ['required', 'max:225'],
            'category_id' => ['required'],
            'image' => ['file', 'nullable', 'max:300'],
        ]);

        $image = $product->image;

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
    
            // Upload gambar baru
            $uploadedImage = $request->file('image');
            $image = $uploadedImage->store('product-images', 'public');
        }

        $product->update([
            'name' => $request->name,
            'buy_price' => $request->buy_price,
            'sell_price' => $request->sell_price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $image,
            'category_id' => $request->category_id,
        ]);

        return redirect('/products');
    }


    public function destroy($id){
        $product = Product::findOrFail($id);
        
        $product->delete();

        return redirect('/products');
    }
}
