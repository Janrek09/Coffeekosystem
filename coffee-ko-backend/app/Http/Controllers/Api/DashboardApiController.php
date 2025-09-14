<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary()
    {
        return response()->json([
            "inventory" => 120,
            "salesToday" => 4500,
            "products" => 45,
            "deliveries" => 5
        ]);
    }

    public function weeklySales()
    {
        return response()->json([
            "labels" => ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            "data" => [1200, 900, 1500, 800, 1700, 2200, 1900]
        ]);
    }

    public function bestSellers()
    {
        return response()->json([
            ["name" => "Latte", "sales" => 40, "color" => "#6D4C41"],
            ["name" => "Cappuccino", "sales" => 25, "color" => "#A1887F"],
            ["name" => "Espresso", "sales" => 20, "color" => "#F59E0B"],
            ["name" => "Mocha", "sales" => 15, "color" => "#3B82F6"]
        ]);
    }

    public function events()
    {
        return response()->json([
            "2025-09-05" => [
                ["title" => "Supplier Delivery - Beans", "time" => "10:00 AM"],
                ["title" => "Team Meeting", "time" => "2:00 PM"],
            ],
            "2025-09-12" => [
                ["title" => "Promo Launch - Mocha", "time" => "9:00 AM"],
                ["title" => "Inventory Check", "time" => "5:00 PM"],
            ],
        ]);
    }
}