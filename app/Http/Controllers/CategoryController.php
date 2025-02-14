<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
        ->when(request()->search, function($categories) {
            $categories = $categories->where('name', 'like', '%'. request()->search . '%');
        })->latest()->paginate(20);

        return Inertia::render('Categories/index', [
            'categories' => $categories
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
            'unique:categories,name' .$category->id
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
