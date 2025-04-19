<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');  

        if ($request->input('sort') === 'most_products') {
            $query->orderBy('products_count', 'desc');
        } else {
            $query->latest();
        }
        
        $categories = $query->paginate(12);
        
        return Inertia::render('Categories/index', [
            'categories' => $categories,
        ]);
    }

    public function searchCategoryName(Request $request)
    {
        $categories = Category::withCount('products')
        ->where('name', 'like', '%' . $request->name . '%')
        ->paginate(12);       

        return Inertia::render('Categories/index', [
            'categories' => $categories,
        ]);
    }

    public function dropDownCategory(Request $request)
    {
        $categories = Category::where('name', 'like', '%' . $request->name . '%')
        ->select(['id', 'name'])
        ->limit(3)  
        ->get();         

        if ($categories->count() > 0) {
            return response()->json([
                'success' => true,
                'data'    => $categories
            ]);
        }

        return response()->json([
            'success' => false,
            'data'    => []
        ]);
    }

    public function create(){
        return Inertia::render('Categories/Create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => [
                'required',
                'max:225',
               'unique:categories,name'
            ],
            'description' => ['required', 'max:225']
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect('/categories')->with('message', 'New Category Created Successfully');
    }

    public function edit(Category $category){
        return Inertia::render('Categories/Edit', [
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category){
        $request->validate([
            'name' => [
            'required',
            'max:225',
            'unique:categories,name,' .$category->id
        ],
            'description' => 'required'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description 
        ]);

        return redirect('/categories')->with('message', 'Category Edited Successfully');
    }

    public function destroy($id){
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect('/categories')->with('message', 'Category Deleted Successfully');
    }
}
