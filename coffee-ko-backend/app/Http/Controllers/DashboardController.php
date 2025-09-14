<?php

namespace App\Http\Controllers;

use App\Models\Inventory;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard view.
     */
    public function index()
    {
        // Inventory summary
        $totalItems   = Inventory::count();
        $lowStock     = Inventory::where('stock', '<=', 5)->count();
        $outOfStock   = Inventory::where('stock', 0)->count();
        $productsTotal= Inventory::sum('stock');

        // Sample dummy for sales (pwede palitan kapag may Sales model ka na)
        $salesToday        = 4500;
        $pendingDeliveries = 5;

        // Best sellers (dito base muna sa stock)
        $bestSellers = Inventory::orderBy('stock', 'desc')
            ->take(5)
            ->pluck('stock', 'name');

        $data = [
            'inventoryItems'    => $totalItems,
            'lowStockCount'     => $lowStock,
            'outOfStockCount'   => $outOfStock,
            'salesToday'        => $salesToday,
            'productsTotal'     => $productsTotal,
            'pendingDeliveries' => $pendingDeliveries,
            'weeklyRevenue'     => [
                'labels' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                'data'   => [1150, 800, 1500, 780, 1600, 2200, 1900], // sample only
            ],
            'bestSellers'       => $bestSellers,
        ];

        return view('dashboard', $data);
    }

    /**
     * JSON summary for AJAX/API (Dashboard Overview)
     */
    public function summary()
    {
        return response()->json([
            'total_items'   => Inventory::count(),
            'low_stock'     => Inventory::where('stock', '<=', 5)->get(),
            'out_of_stock'  => Inventory::where('stock', 0)->get(),
            'products_total'=> Inventory::sum('stock'),
        ]);
    }

    /**
     * JSON endpoint for "Ingredients Box" in dashboard
     */
    public function ingredientsBox()
    {
        return response()->json(
            Inventory::select('id', 'name', 'stock', 'category', 'price')
                ->orderBy('name')
                ->get()
        );
    }
}
