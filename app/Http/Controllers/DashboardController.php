<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\FrontOffice;
use Illuminate\Http\Request;
use App\Models\LoginLog;
use App\Models\Order;
use App\Models\OrderItem;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


    //get Dashboard
    public function overviewDashboard()
    {

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();


        $totalExpenses     = Expense::sum('amount');
        $monthExpenses     = Expense::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');
        $weekExpenses      = Expense::whereBetween('date', [$startOfWeek, $endOfWeek])->sum('amount');
        $todayExpenses     = Expense::whereDate('date', $today)->sum('amount');
        $yesterdayExpenses = Expense::whereDate('date', $yesterday)->sum('amount');

        /** ---------------- SALES (Quantity Delivered) ---------------- */
        $totalQty     = OrderItem::whereHas('order', fn($q) => $q->where('delivery_status', 2))->sum('quantity');
        $monthQty     = OrderItem::whereHas('order', fn($q) => $q->where('delivery_status', 2)->whereBetween('date', [$startOfMonth, $endOfMonth]))->sum('quantity');
        $weekQty      = OrderItem::whereHas('order', fn($q) => $q->where('delivery_status', 2)->whereBetween('date', [$startOfWeek, $endOfWeek]))->sum('quantity');
        $todayQty     = OrderItem::whereHas('order', fn($q) => $q->where('delivery_status', 2)->whereDate('date', $today))->sum('quantity');
        $yesterdayQty = OrderItem::whereHas('order', fn($q) => $q->where('delivery_status', 2)->whereDate('date', $yesterday))->sum('quantity');

        /** ---------------- EARNINGS (Paid Amounts) ---------------- */
        $totalEarnings     = Order::where('payment_status', 1)->sum('total_amount');
        $monthEarnings     = Order::where('payment_status', 1)->whereBetween('date', [$startOfMonth, $endOfMonth])->sum('total_amount');
        $weekEarnings      = Order::where('payment_status', 1)->whereBetween('date', [$startOfWeek, $endOfWeek])->sum('total_amount');
        $todayEarnings     = Order::where('payment_status', 1)->whereDate('date', $today)->sum('total_amount');
        $yesterdayEarnings = Order::where('payment_status', 1)->whereDate('date', $yesterday)->sum('total_amount');

        /** ---------------- PENDING AMOUNTS ---------------- */
        $pendingAmount = Order::where('payment_status', 0)->sum('total_amount');

        /** ---------------- DELIVERY STATUS ---------------- */
        $deliveryStatus = [
            'pending'   => Order::where('delivery_status', 0)->count(),
            'shipped'   => Order::where('delivery_status', 1)->count(),
            'delivered' => Order::where('delivery_status', 2)->count(),
            'cancelled' => Order::where('delivery_status', 3)->count(),
        ];

        /** ---------------- PROFIT / LOSS ---------------- */
        $profitOrLoss = $totalEarnings - $totalExpenses;


        return view('dashboard.overview.index', [
            'expenses' => [
                'total'      => $totalExpenses,
                'month'      => $monthExpenses,
                'week'       => $weekExpenses,
                'today'      => $todayExpenses,
                'yesterday'  => $yesterdayExpenses,
            ],
            'sales' => [
                'totalQty'   => $totalQty,
                'monthQty'   => $monthQty,
                'weekQty'    => $weekQty,
                'todayQty'   => $todayQty,
                'yesterdayQty' => $yesterdayQty,
            ],
            'earnings' => [
                'total'      => $totalEarnings,
                'month'      => $monthEarnings,
                'week'       => $weekEarnings,
                'today'      => $todayEarnings,
                'yesterday'  => $yesterdayEarnings,
                'pending'    => $pendingAmount,
            ],
            'deliveryStatus' => $deliveryStatus,
            'profitOrLoss'   => $profitOrLoss,
        ]);
    }

    //Chart Dashboard
    public function chartDashboard()
    {
        return view('dashboard.chart.index');
    }

    //Product data
    public function productData()
    {
        $orderItems = OrderItem::whereHas('order', function ($q) {
            $q->where('delivery_status', 2); // delivered
        })->with('product')->get();

        $productQuantities = [];
        foreach ($orderItems as $item) {
            $name = $item->product->name ?? 'Unknown';
            $productQuantities[$name] = ($productQuantities[$name] ?? 0) + $item->quantity;
        }

        return response()->json([
            'productNames' => array_keys($productQuantities),
            'quantities' => array_values($productQuantities)
        ]);
    }

    //Last 30 Days Sales
    public function last30DaysSales()
    {
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $sales = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.delivery_status', 2)
            ->whereBetween('orders.date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(orders.date) as order_date'),
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->get();

        $labels = [];
        $quantities = [];

        $period = [];
        for ($i = 0; $i < 30; $i++) {
            $period[] = $startDate->copy()->addDays($i)->format('Y-m-d');
        }

        foreach ($period as $day) {
            $labels[] = Carbon::parse($day)->format('d M');
            $quantities[] = optional($sales->firstWhere('order_date', $day))->total_quantity ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'quantities' => $quantities
        ]);
    }

    public function financialOverview()
    {
        // Total payment received
        $totalIncome = DB::table('orders')
            ->where('payment_status', 1) // paid orders
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->select(DB::raw('SUM(order_items.price * order_items.quantity) as total'))
            ->value('total') ?? 0;

        // Total expenses
        $totalExpense = DB::table('expenses')->sum('amount') ?? 0;

        return response()->json([
            'labels' => ['Income', 'Expenses'],
            'series' => [$totalIncome, $totalExpense]
        ]);
    }

    public function last30DaysIncome()
    {
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Total income per day from paid orders
        $incomeData = DB::table('orders')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 1) // only paid orders
            ->whereBetween('orders.date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(orders.date) as order_date'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_income')
            )
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->get();

        // Prepare labels and series arrays
        $labels = [];
        $series = [];

        // Generate last 30 days
        for ($i = 0; $i < 30; $i++) {
            $day = $startDate->copy()->addDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($day)->format('d M'); // e.g., 26 Sep
            $series[] = optional($incomeData->firstWhere('order_date', $day))->total_income ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'series' => $series
        ]);
    }
}
