<?php

namespace App\Http\Controllers;

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
        $total_sales = Transaction::where('chasier_id', getUserIdForQuery())
        ->whereDate('created_at', Carbon::today())->sum('grand_total');

        $profit = Profit::whereDate('created_at', Carbon::today())->sum('total');
        
        $total_employees = User::where('parent_id', getUserIdForQuery())->count();
        $total_outlets = Outlet::where('user_id', getUserIdForQuery())->count();
        $stock_product = Product::where('user_id', getUserIdForQuery())->where('stock', '<=', '10')->limit(5)->get();

        $week = Carbon::now()->subDays(7);

        // Chart Sales Seminggu Terakhir
        $chart_sales_week = DB::table('transactions')->
        where('chasier_id', getUserIdForQuery())
        ->addSelect(DB::raw('DATE(created_at) as date, SUM(grand_total) as grand_total'))
        ->where('created_at', '>=', $week)
        ->groupBy('date')
        ->get();

        if(count($chart_sales_week)) {
            foreach ($chart_sales_week as $result) {
                $sales_date[]    = $result->date;
                $grand_total[]   = (int)$result->grand_total;
            }
        }else {
            $sales_date[]   = "";
            $grand_total[]  = "";
        }

        // Chart Profits Seminggu Terakhir
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

        // Best Outltes
        $best_outlets = DB::table('outlets')
        ->where('user_id', getUserIdForQuery())
        ->select(
            'outlets.name as outlet_name',  // Mengambil nama outlet
            DB::raw('COUNT(transactions.id) as transaction_count')  // Menghitung jumlah transaksi
        )
        ->join('users', 'outlets.id', '=', 'users.outlet_id')  // Menggabungkan tabel outlets dengan users berdasarkan outlet_id
        ->join('transactions', 'users.id', '=', 'transactions.chasier_id')  // Menggabungkan tabel users dengan transactions berdasarkan cashier_id
        ->groupBy('outlets.id', 'outlets.name')  // Kelompokkan berdasarkan id outlet dan nama outlet
        ->orderByDesc('transaction_count')  // Urutkan berdasarkan jumlah transaksi terbanyak
        ->limit(5)  // Batasi hasilnya hanya 5 yang terbanyak
        ->get();  // Ambil hasil query
    
        if(count($best_outlets)) {
            foreach ($best_outlets as $result) {
                $outlet_name[]    = $result->outlet_name;
                $transaction_count[]   = (int)$result->transaction_count;
            }
        }else {
            $outlet_name[]   = "";
            $transaction_count[]  = "";
        }

        // Best Product
        $best_products = DB::table('transaction_details')
            ->where('user_id', getUserIdForQuery())
            ->addSelect(DB::raw('products.name as name, SUM(transaction_details.qty) as total'))
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->groupBy('transaction_details.product_id')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get();

        // Best Employees
        $best_employees = DB::table('transactions')
        ->where('chasier_id', getUserIdForQuery())
        ->orWhereIn('chasier_id', Auth::user()->employees->pluck('id'))

        ->select(
            'users.name as name',
            DB::raw('COUNT(transactions.id) as total')  // Menghitung jumlah transaksi yang dilakukan oleh karyawan
        )
        ->join('users', 'users.id', '=', 'transactions.chasier_id')  // Menggabungkan tabel users dengan transaksi berdasarkan cashier_id
        ->groupBy('users.id')  // Mengelompokkan berdasarkan ID karyawan
        ->orderBy('total', 'DESC')  // Mengurutkan berdasarkan jumlah transaksi terbanyak
        ->limit(5)
        ->get();   
        
        
        if(count($best_employees)) {
            foreach ($best_employees as $result) {
                $employees_name[]    = $result->name;
                $transaction_count_employees[]   = (int)$result->total;
            }
        }else {
            $employees_name[]   = "";
            $transaction_count_employees[]  = "";
        }

        return Inertia::render("Dashboard/index", [
            'total_sales' => $total_sales,
            'profit' => $profit,
            'total_employees' => $total_employees,
            'total_outlets' => $total_outlets,

            'sales_date_week' =>  $sales_date,
            'grand_total_week' => $grand_total,

            'profits_date_week' =>  $sales_date,
            'profits_total_week' => $profits_total,

            'best_outlets' => $outlet_name,
            'best_outlets_transaction' => $transaction_count,

            'best_employees' => $employees_name,
            'transaction_count_employees' => $transaction_count_employees,

            'best_product' => $best_products,

            'stock_product' => $stock_product
        ]);
    }
}
