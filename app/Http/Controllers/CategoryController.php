<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())
        ->withCount('products')
        ->when(request()->search, function($categories) {
            $categories = $categories->where('name', 'like', '%'. request()->search . '%');
        })->latest()->get();

        return Inertia::render('Categories/index', [
            'categories' => $categories
        ]);
    }

    public function create(){
        return Inertia::render('Categories/Create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required','unique:categories,name,', 'max:225'],
            'description' => ['required', 'max:225']
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::id()
        ]);

        return redirect('/categories');
    }

    public function edit(Category $category){
        return Inertia::render('Categories/Edit', [
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category){
        $request->validate([
            'name' => 'required|unique:categories,name,'.$category->id,
            'description' => 'required'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description 
        ]);

        return redirect('/categories');
    }

    public function destroy($id){
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect('/categories');
    }
}
