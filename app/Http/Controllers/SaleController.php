<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleController extends Controller
{
    public function index(){
        $sales = Transaction::with('cashier')->with('customers')
        ->whereDate('created_at', Carbon::today())
        ->paginate(20);

        $transaction_detail = TransactionDetail::with('product')->latest()->get();

        return Inertia::render('Sales/index', [
            'sales' => $sales,
            'transaction_detail' => $transaction_detail,
        ]);
    }

    public function filter(Request $request)
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

        return Inertia::render('Sales/index', [
            'sales'    => $sales,
            'transaction_detail' => $transaction_detail
        ]);
    }


}
