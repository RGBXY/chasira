<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Outlet;
use App\Models\Product;
use App\Models\Profit;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        $sale = Transaction::whereDate('created_at', Carbon::today())->sum('grand_total');

        $profit = Profit::whereDate('created_at', Carbon::today())->sum('total');
        
        $total_employees = User::where('id', '!=', 1)->count();

        $total_customers = Customers::where('id', '!=', 1)->count();

        $stock_product = Product::where('stock', '<=', '10')->select(['id', 'name', 'stock'])->limit(5)->get();

        $week = Carbon::now()->subWeek();
        $week = Carbon::now()->subMonth();

        $chart_sales_week = DB::table('transactions')
            ->where('created_at', '>=', $week)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as grand_total'))
            ->groupBy('date')
            ->get();

        $sales_date_week = [];
        $grand_total = [];

        if ($chart_sales_week->count()) {
            foreach ($chart_sales_week as $result) {
                $sales_date_week[]  = $result->date;
                $grand_total[] = (int) $result->grand_total;
            }
        } else {
            $sales_date_week[]  = "";
            $grand_total[] = "";
        }

        $chart_profits_week = DB::table('profits')
        ->addSelect(DB::raw('DATE(created_at) as date, SUM(total) as profits_total'))
        ->where('created_at', '>=', $week)
        ->groupBy('date')
        ->get();

        if(count($chart_profits_week)) {
            foreach ($chart_profits_week as $result) {
                $profits_date[]    = $result->date;
                $profits_total[]   = (int)$result->profits_total;
            }
        }else {
            $profits_date[]   = "";
            $profits_total[]  = "";
        }

        $best_products = DB::table('transaction_details')
        ->select(DB::raw('products.name as name, SUM(transaction_details.qty) as total'))
        ->join('products', 'products.id', '=', 'transaction_details.product_id')
        ->groupBy('transaction_details.product_id', 'products.name')
        ->whereDate('transaction_details.created_at', '>=', $week)
        ->orderByDesc('total')
        ->limit(5)
        ->get();


        return Inertia::render('Dashboard/index', [
            'sale'             => $sale,
            'profit'           => $profit,
            'total_employees'  => $total_employees,
            'total_customers'  => $total_customers,

            'sales_date_week'  => $sales_date_week,
            'grand_total_week'  => $grand_total,

            'profits_date_week'  => $profits_date,
            'profits_total_week'  => $profits_total,

            'best_products'  => $best_products,

            'stock_product' => $stock_product
        ]); 
    }

    
}
