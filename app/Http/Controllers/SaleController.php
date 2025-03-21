<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleController extends Controller
{
    public function index(){
        $sales = Transaction::with('cashier:id,name')->with('customers:id,name')
        ->whereDate('created_at', Carbon::today())
        ->paginate(20);

        return Inertia::render('Sales/index', [
            'sales' => $sales,
        ]);
    }

    public function transaction_detail_filter(Request $request){
        $transaction_detail = TransactionDetail::with('product:id,name,buy_price')
        ->where('transaction_id', $request->id )->first();

        $sale_detail = Transaction::where('id', $request->id )
        ->with('cashier:id,name')->with('customers:id,name')->first();

        if ($transaction_detail) {
            return response()->json([
                'success'             => true,
                'data'                => $sale_detail,
                'transaction_data'    => $transaction_detail
            ]);
        }

        return response()->json([
            'success'           => false,
            'data'              => [],
            'transaction_data'  => [],
        ]);
    }

    public function filterDate(Request $request)
    {
        $request->validate([
            'start_date'  => 'required',
            'end_date'    => 'required',
        ]);

        //get data sales by range date
        $sales = Transaction::with('cashier')->with('customers')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->paginate(20);

        $transaction_detail = TransactionDetail::with('product')->latest()->get();

        if ($transaction_detail) {
            return response()->json([
                'success'             => true,
                'data'           => $sales,
                'transaction_data'    => $transaction_detail,
            ]);
        }

        return response()->json([
            'success'           => false,
            'data'         => [],
            'transaction_data'  => [],
        ]);
    }


}
