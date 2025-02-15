<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('category')
        ->when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
        })
        ->when(request()->category_id, function ($query) {
            $query->where('category_id', request()->category_id);
        })
        ->latest()
        ->paginate(20);

         return Inertia::render('Products/index', [
        'products' => $products,
        'categories' => Category::all(),
    ]); 
    }

    public function create(){
        $categories = Category::latest()->get();
        return Inertia::render('Products/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request){       
        $request->validate([
            'name' => ['required', 'max:225', 'unique:products,name,'],
            'barcode' => ['required', 'unique:products,barcode,'],
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
            'barcode' => $request->barcode,
            'category_id' => $request->category_id,
        ]);

        return redirect('/products')->with('message', 'Product Added Succesfully');
    }

    public function edit(Product $product){
        $categories = Category::latest()->get();

        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Product $product){       

        $request->validate([
           'name' => [
                'required',
                'max:225',
               'unique:products,name,'.$product->id
            ],
            'buy_price' => ['required','numeric'],
            'sell_price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
            'description' => ['required', 'max:225'],
            'category_id' => ['required'],
            'barcode' => ['required', 'unique:products,barcode,' .$product->id],
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
            'barcode' => $request->barcode
        ]);

        return redirect('/products')->with('message', 'Product Edited Succesfully');
    }


    public function destroy($id){
        $product = Product::findOrFail($id);
        
        $product->delete();

        return redirect('/products')->with('message', 'Product Deleted Succesfully');
    }
}
