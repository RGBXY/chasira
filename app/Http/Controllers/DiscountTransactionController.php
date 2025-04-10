<?php

namespace App\Http\Controllers;

use App\Models\DiscountTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DiscountTransactionController extends Controller
{
    public function index(){
        $discount_transactions = DiscountTransaction::paginate(12);

        return Inertia::render("Discount_Transaction/index", [
            'discount' => $discount_transactions
        ]);
    }

    public function create(){ 
        return Inertia::render("Discount_Transaction/Create");
    }

    public function searchDiscountName(Request $request)
    {
        $discount = DiscountTransaction::where('name', 'like', '%' . $request->name . '%')
                    ->limit(12)  
                    ->get();       

        if ($discount->count() > 0) {
            return response()->json([
                'success' => true,
                'data'    => $discount
            ]);
        }

        return response()->json([
            'success' => false,
            'data'    => []
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'max:225', 'unique:discount_transactions,name'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'discount' => ['required'],
            'minimal_transaction' => ['required'],
            'customer_only' => ['required'],
            'description' => ['required'],
        ]);

        DiscountTransaction::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
            'discount' => $request->discount,
            'minimal_transaction' => $request->minimal_transaction,
            'customer_only' => $request->customer_only,
            'description' => $request->description,
        ]);

        return redirect('/discount-transactions')->with('message', 'Discount Added Successfully');
    }

    public function edit($id){
        $discount = DiscountTransaction::findOrFail($id);

        return Inertia::render('Discount_Transaction/Edit', [
            'discount' => $discount,
        ]);
    }

    public function update(Request $request, DiscountTransaction $discount_transaction){
        // dd($discount_transaction);

        $request->validate([
            'name' => ['required', 'unique:discount_transactions,name,'.$discount_transaction->id],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'discount' => ['required'],
            'minimal_transaction' => ['required'],
            'customer_only' => ['required'],
            'description' => ['required'],
        ]);

        $discount_transaction->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'discount' => $request->discount,
            'status' => $request->status,
            'minimal_transaction' => $request->minimal_transaction,
            'customer_only' => $request->customer_only ? '1' : '0',
            'description' => $request->description,
        ]);
        
        return redirect("/discount-transactions")->with('message', 'Discount Edited Successfully');
    }

    public function destroy($id){
        $discount = DiscountTransaction::findOrFail($id);
        $discount->delete();

        return redirect()->back()->with('message', 'Discount Data Deleted Successfully');
    }

    public function deactivate($id)
    {
        $discount = DiscountTransaction::findOrFail($id);
        $discount->update(['status' => 'inactive']);
    }

    public function activate($id){
        $discount = DiscountTransaction::findOrFail($id);
        $discount->update(['status' => 'active']);
    }

}
