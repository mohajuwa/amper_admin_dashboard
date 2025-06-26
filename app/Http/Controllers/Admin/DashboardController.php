<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        config(['app.timezone' => 'UTC']); // Match your DB timezone

        $data['TotalOrders'] = OrderModel::getTotalOrders();
        $data['TodayTotalOrders'] = OrderModel::getTodayOrders();

        $data['TotalAmount'] = OrderModel::getTotalAmount();
        $data['TodayTotalAmount'] = OrderModel::getTodayAmount();

        // Updated user count methods
        $data['TotalCustomers'] = User::where('status', 'active')->count();
        $data['TodayTotalCustomers'] = User::whereDate('created_at', today())->count();

        $data['LatestOrders'] = OrderModel::getLatestOrders();

        $year = $request->year ?? date('Y');

        $getTotalCustomersMonth = [];
        $getTotalOrdersMonth = [];
        $getTotalAmountMonth = [];
        $totalAmount = 0;

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            // Active customers for the month
            $customerCount = User::where('status', 'active')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $getTotalCustomersMonth[] = $customerCount;

            // Orders for the month
            $orderCount = OrderModel::whereBetween('order_date', [$startDate, $endDate])
                ->count();
            $getTotalOrdersMonth[] = $orderCount;

            // Order amounts for the month
            $orderAmount = OrderModel::getTotalAmountMonth($startDate, $endDate);
            $getTotalAmountMonth[] = $orderAmount;

            $totalAmount += $orderAmount;
        }

        $data['getTotalCustomersMonth'] = $getTotalCustomersMonth;
        $data['getTotalOrdersMonth'] = $getTotalOrdersMonth;
        $data['getTotalAmountMonth'] = $getTotalAmountMonth;


        $data['totalAmount'] = $totalAmount;
        $data['year'] = $year;
        // dd($data['getTotalAmountMonth']);

        $data['header_title'] = 'Dashboard';
        return view('admin.dashboard', $data);
    }
}