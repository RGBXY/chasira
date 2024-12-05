<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Profit;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        $total_sales = Transaction::whereDate('created_at', Carbon::today())->sum('grand_total');
        $profit = Profit::whereDate('created_at', Carbon::today())->sum('total');
        $total_employees = User::where('parent_id', Auth::id())->count();
        $total_outlets = Outlet::where('user_id', Auth::id())->count();

        return Inertia::render("Dashboard/index", [
            'total_sales' => $total_sales,
            'profit' => $profit,
            'total_employees' => $total_employees,
            'total_outlets' => $total_outlets
        ]);
    }
}
