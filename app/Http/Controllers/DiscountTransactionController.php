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
                    ->paginate(12);      

        return Inertia::render("Discount_Transaction/index", [
            'discount' => $discount
        ]);
    }

    public function searchDiscountCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'nullable|numeric',
        ]);

        $discount = DiscountTransaction::where('code', $request->code)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon code is not valid or has expired',
            ]);
        }

        // Validasi jika kupon hanya untuk member
        if ($discount->customer_only && !$request->has('customer_id')) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is for members only',
            ]);
        }

        // Validasi minimum transaksi (jika ada)
        if ($discount->minimal_transaction && $request->subtotal < $discount->minimal_transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Subtotal does not meet the minimum requirement',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $discount
        ]);
    }
 

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'max:225', 'unique:discount_transactions,name'],
            'start_date' => ['required', 'date'],
            'code' => ['required'],
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
            'code' => $request->code,
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
        $request->validate([
            'name' => ['required', 'unique:discount_transactions,name,'.$discount_transaction->id],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'discount' => ['required'],
            'code' => ['required'],
            'minimal_transaction' => ['required'],
            'customer_only' => ['required'],
            'description' => ['required'],
        ]);

        $discount_transaction->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'discount' => $request->discount,
            'code' => $request->code,
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

}
