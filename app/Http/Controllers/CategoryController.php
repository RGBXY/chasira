<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'max:225'],
            'description' => ['required', 'max:225']
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('categories');
    }
}
