<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
    }
    public function get()
    {
        $order_query = Invoice::where('order_status', 'delivered')->with('orderItems');
        $delivery_orders_count = $order_query->count();
        $sales_items_count = 0;
        foreach ($order_query->get() as $order) {
            $sales_items_count += $order->orderItems()->count();
        }
        $users_count = User::where('role', 'user')->count();
        $eymploee_count = User::where('role', 'employee')->count();
        $monthly_sales = $this->monthly_sales();

        return [
            'delivery_orders_count' => $delivery_orders_count,
            'sales_items_count' => $sales_items_count,
            'users_count' => $users_count,
            'eymploee_count' => $eymploee_count,
            'monthly_revenue' => $monthly_sales->values(),
        ];
    }

    public function monthly_sales()
    {
        $currentYear = date('Y');
        $monthly_revenue = Invoice::where('order_status', 'delivered')
            ->whereYear('created_at', $currentYear) // Filter by the current year
            ->selectRaw('MONTH(created_at) as month, SUM(total) as revenue') // Assuming 'total' is the column storing the revenue
            ->groupBy('month') // Group by month
            ->orderBy('month') // Order by month
            ->get();

        // If you want the months in English (or another locale):
        $monthNames = collect(range(1, 12))->map(function ($month) {
            return Carbon::create()->month($month)->locale('en')->isoFormat('MMM'); // Get month name in locale 'en'
        });

        // Initialize an array with all 12 months, setting revenue to 0 initially
        $monthly_sales = collect(range(1, 12))->mapWithKeys(function ($month) use ($monthNames) {
            return [
                $month => [
                    'month' => $monthNames[$month - 1], // Get the month name dynamically
                    'revenue' => 0 // Default revenue for each month is 0
                ]
            ];
        });

        // Merge the result from the query with the initialized array
        $monthly_revenue->each(function ($item) use ($monthly_sales) {
            $monthly_sales[$item->month] = [
                'month' => $monthly_sales[$item->month]['month'], // Keep the month name
                'revenue' => $item->revenue // Update with actual revenue
            ];
        });

        return $monthly_sales;
    }
}
