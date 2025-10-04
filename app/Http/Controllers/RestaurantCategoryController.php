<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestaurantCategory;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RestaurantCategoryController extends Controller
{
    /**
     * Display a listing of categories for a restaurant
     */
    public function index(Request $request, $restaurantId)
    {
        try {
            $categories = RestaurantCategory::where('restaurant_id', $restaurantId)
                ->orderBy('name')
                ->get(['id', 'name', 'description', 'image', 'is_active', 'created_at']);

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified category
     */
    public function show($restaurantId, $id)
    {
        try {
            $category = RestaurantCategory::where('restaurant_id', $restaurantId)
                ->where('id', $id)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request, $restaurantId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if category name already exists for this restaurant
            $existingCategory = RestaurantCategory::where('restaurant_id', $restaurantId)
                ->where('name', $request->name)
                ->first();

            if ($existingCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category name already exists for this restaurant'
                ], 422);
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('restaurant-categories', $imageName, 'public');
            }

            $category = RestaurantCategory::create([
                'restaurant_id' => $restaurantId,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $restaurantId, $id)
    {
        try {
            $category = RestaurantCategory::where('restaurant_id', $restaurantId)
                ->where('id', $id)
                ->firstOrFail();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if category name already exists for this restaurant (excluding current category)
            $existingCategory = RestaurantCategory::where('restaurant_id', $restaurantId)
                ->where('name', $request->name)
                ->where('id', '!=', $id)
                ->first();

            if ($existingCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category name already exists for this restaurant'
                ], 422);
            }

            // Handle image upload
            $imagePath = $category->image; // Keep existing image
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('restaurant-categories', $imageName, 'public');
            }

            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy($restaurantId, $id)
    {
        try {
            $category = RestaurantCategory::where('restaurant_id', $restaurantId)
                ->where('id', $id)
                ->firstOrFail();

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of the category.
     */
    public function toggle($restaurantId, $id)
    {
        try {
            $category = RestaurantCategory::where('restaurant_id', $restaurantId)
                ->where('id', $id)
                ->firstOrFail();

            $category->update(['is_active' => !$category->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Category status updated successfully',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle category status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}