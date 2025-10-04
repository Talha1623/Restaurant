<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    /**
     * Store a newly created ingredient.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'allergen_info' => 'nullable|string|max:255'
        ]);

        // Check if ingredient name already exists
        $existingIngredient = Ingredient::where('name', $request->name)->first();

        if ($existingIngredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient name already exists.'
            ], 422);
        }

        $ingredient = Ingredient::create([
            'name' => $request->name,
            'allergen_info' => $request->allergen_info
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ingredient added successfully!',
            'ingredient' => $ingredient
        ]);
    }

    /**
     * Update the specified ingredient.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'allergen_info' => 'nullable|string|max:255'
        ]);

        $ingredient = Ingredient::findOrFail($id);

        // Check if ingredient name already exists (excluding current ingredient)
        $existingIngredient = Ingredient::where('name', $request->name)
            ->where('ingredient_id', '!=', $id)
            ->first();

        if ($existingIngredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient name already exists.'
            ], 422);
        }

        $ingredient->update([
            'name' => $request->name,
            'allergen_info' => $request->allergen_info
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ingredient updated successfully!',
            'ingredient' => $ingredient
        ]);
    }

    /**
     * Remove the specified ingredient.
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ingredient deleted successfully!'
        ]);
    }
}