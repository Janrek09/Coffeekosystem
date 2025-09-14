<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Show all inventory items (Blade or JSON).
     */
    public function index(Request $request)
    {
        $items = Inventory::all();

        if ($request->wantsJson()) {
            return response()->json($items);
        }

        return view('inventory.index', compact('items'));
    }

    /**
     * Show a single inventory item.
     */
    public function show(Inventory $inventory, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json($inventory);
        }

        return view('inventory.show', compact('inventory'));
    }

    /**
     * Store a new inventory item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'stock'     => 'required|integer|min:0',
            'price'     => 'nullable|numeric|min:0',
            'threshold' => 'nullable|integer|min:0',
        ]);

        $item = Inventory::create($validated);

        if ($request->wantsJson()) {
            return response()->json($item, 201);
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Item added successfully!');
    }

    /**
     * Update an inventory item.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'stock'     => 'required|integer|min:0',
            'price'     => 'nullable|numeric|min:0',
            'threshold' => 'nullable|integer|min:0',
        ]);

        $inventory->update($validated);

        if ($request->wantsJson()) {
            return response()->json($inventory);
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Item updated successfully!');
    }

    /**
     * Delete an inventory item.
     */
    public function destroy(Request $request, Inventory $inventory)
    {
        $inventory->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Item deleted successfully!');
    }
}
