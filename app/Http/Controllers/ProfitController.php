<?php

namespace App\Http\Controllers;

use App\Models\Profit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfitController extends Controller
{
    public function index(){
        $profits = Profit::with('transaction:id,invoice')
        ->whereDate('created_at', Carbon::today())
        ->paginate(20);

        $total = Profit::whereDate('created_at', Carbon::today())
        ->sum('total');

        return Inertia::render('Profits/index', [
            'profits' => $profits,
            'total' => $total,
        ]);

    }

    public function filterDate(Request $request)
    {
        $profits = Profit::with('transaction:id,invoice')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->latest()
            ->paginate(10);

        $total = Profit::whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->sum('total');

        // dd($profits);

        return Inertia::render('Profits/index', [
            'profits' => $profits,
            'total' => $total,
        ]);
    }    
}
