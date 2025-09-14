<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        return Ingredient::all();
    }

    public function store(Request $request)
    {
        $ingredient = Ingredient::create($request->all());
        return response()->json($ingredient, 201);
    }

    public function show($id)
    {
        return Ingredient::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->update($request->all());
        return response()->json($ingredient, 200);
    }

    public function destroy($id)
    {
        Ingredient::destroy($id);
        return response()->json(null, 204);
    }
}