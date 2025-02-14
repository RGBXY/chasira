<?php

namespace App\Http\Controllers;

use App\Models\Profit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfitController extends Controller
{
    public function index(){
        $profits = Profit::with('transaction')
        ->whereDate('created_at', Carbon::today())
        ->paginate(20);

        $total = Profit::whereDate('created_at', Carbon::today())
        ->sum('total');

        return Inertia::render('Profits/index', [
            'profits' => $profits,
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
        $profits = Profit::with('transaction')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->paginate(20);

        //get total sales by range date
        $total = Profit::whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->sum('total');

        return Inertia::render('Profits/index', [
            'profits'    => $profits,
            'total'    => (int) $total
        ]);
    }
}
