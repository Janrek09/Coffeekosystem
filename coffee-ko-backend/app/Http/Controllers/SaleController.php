<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        return Sale::with('inventory','user')->orderByDesc('created_at')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'inventory_id'=>'required|exists:inventories,id',
            'quantity'=>'required|integer|min:1',
            'unit_price'=>'required|numeric|min:0'
        ]);

        $inventory = Inventory::findOrFail($data['inventory_id']);

        if ($inventory->stock < $data['quantity']) {
            return response()->json(['message'=>'Not enough stock'], 422);
        }

        $total = $data['quantity'] * $data['unit_price'];

        $sale = DB::transaction(function() use ($data, $inventory, $request, $total) {
            // reduce stock
            $inventory->stock -= $data['quantity'];
            $inventory->save();

            // create sale
            $sale = Sale::create([
                'inventory_id'=>$inventory->id,
                'sold_by'=> $request->user() ? $request->user()->id : null,
                'quantity'=>$data['quantity'],
                'unit_price'=>$data['unit_price'],
                'total_price'=>$total,
            ]);

            return $sale;
        });

        return response()->json($sale->load('inventory','user'), 201);
    }

    public function show(Sale $sale)
    {
        return response()->json($sale->load('inventory','user'));
    }
}