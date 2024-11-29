<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaleController extends Controller
{
    public function index(){
        $sales = Transaction::with('chasier')
        ->whereDate('created_at', Carbon::today())
        ->get();

        $total = Transaction::whereDate('created_at', Carbon::today())
        ->sum('grand_total');

        return Inertia::render('Sales/index', [
            'sales' => $sales,
            'total' => $total,
        ]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start_date'  => 'required',
            'end_date'    => 'required',
        ]);

        //get data sales by range date
        $sales = Transaction::with('chasier')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->get();

        //get total sales by range date
        $total = Transaction::whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->sum('grand_total');

        return Inertia::render('Sales/index', [
            'sales'    => $sales,
            'total'    => (int) $total
        ]);
    }


}
