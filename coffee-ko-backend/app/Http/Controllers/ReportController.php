<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Sales summary for last N days
     */
    public function salesSummary(Request $request)
    {
        $days = (int) $request->get('days', 7);

        $data = Sale::selectRaw('DATE(created_at) as date, SUM(total_price) as total_sales, SUM(quantity) as total_items')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->limit($days)
            ->get();

        return response()->json($data);
    }

    /**
     * Low stock items (default threshold = 5)
     */
    public function lowStock(Request $request)
    {
        $threshold = (int) $request->get('threshold', 5);

        $low = Inventory::where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json($low);
    }

    /**
     * Inventory summary grouped by category
     */
    public function inventorySummary()
    {
        $byCategory = Inventory::selectRaw('category, SUM(stock) as total_stock, COUNT(*) as item_count')
            ->groupBy('category')
            ->get();

        return response()->json($byCategory);
    }

    /**
     * Sales breakdown by category (Revenue = stock * price)
     */
    public function sales()
    {
        $sales = Inventory::select('category')
            ->selectRaw('SUM(stock * price) as revenue')
            ->groupBy('category')
            ->get();

        return view('reports', compact('sales'));
    }
}
