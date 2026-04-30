<?php

namespace App\Http\Controllers;

use App\Models\FinanceRecord;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Production;
use App\Models\QualityInspection;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard with KPI widgets and chart data.
     */
    public function index()
    {
        $data = [
            'totalProducts'     => Product::where('status', 'Active')->count(),
            'pendingProduction'  => Production::where('status', 'Pending')->count(),
            'passedInspections'  => QualityInspection::where('result', 'Pass')
                                        ->whereMonth('inspection_date', now()->month)->count(),
            'failedInspections'  => QualityInspection::where('result', 'Fail')
                                        ->whereMonth('inspection_date', now()->month)->count(),
            'lowStockItems'      => Inventory::where('quantity_available', '<', 10)->count(),
            'totalMonthCost'     => FinanceRecord::whereMonth('record_date', now()->month)->sum('amount'),
            'monthlyProduction'  => Production::selectRaw('MONTH(production_date) as month, SUM(quantity_produced) as total')
                                        ->whereYear('production_date', now()->year)
                                        ->groupBy('month')->orderBy('month')
                                        ->pluck('total', 'month'),
            'costBreakdown'      => FinanceRecord::selectRaw('cost_type, SUM(amount) as total')
                                        ->whereMonth('record_date', now()->month)
                                        ->groupBy('cost_type')
                                        ->pluck('total', 'cost_type'),
        ];

        return view('dashboard', $data);
    }
}
