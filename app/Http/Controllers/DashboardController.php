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


class DashboardController extends Controller
{


    //get Dashboard
    public function getDashboard()
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


        return view('dashboard.index', [
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
}
