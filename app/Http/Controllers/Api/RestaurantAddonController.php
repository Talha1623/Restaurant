<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantAddon;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RestaurantAddonController extends Controller
{
    /**
     * Display a listing of addons for a restaurant.
     */
    public function index($restaurantId)
    {
        try {
            $restaurant = Restaurant::findOrFail($restaurantId);
            $addons = RestaurantAddon::where('restaurant_id', $restaurantId)
                ->orderBy('name')
                ->get()
                ->map(function($addon) {
                    return [
                        'id' => $addon->id,
                        'name' => $addon->name,
                        'price' => $addon->price ? (float)$addon->price : 0,
                        'description' => $addon->description,
                        'image' => $addon->image,
                        'image_url' => $addon->image ? asset('storage/' . $addon->image) : null,
                        'is_active' => $addon->is_active,
                        'created_at' => $addon->created_at,
                        'updated_at' => $addon->updated_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Addons retrieved successfully',
                'data' => $addons
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving addons: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created addon with restaurant_id in form-data
     */
    public function storeWithIdInBody(Request $request)
    {
        try {
            // Validate the incoming request to ensure 'restaurant_id' is present
            $validator = Validator::make($request->all(), [
                'restaurant_id' => 'required|integer|exists:restaurants,id',
                'name' => 'required|string|max:255',
                'price' => 'nullable|numeric|min:0',
                'description' => 'nullable|string|max:1000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'nullable|in:true,false,1,0,"true","false","1","0"'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Retrieve the restaurant_id from the request body
            $restaurantId = $request->input('restaurant_id');
            $restaurant = Restaurant::findOrFail($restaurantId);

            $data = $request->except(['restaurant_id']);

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('restaurant-addons', 'public');
            }

            // Set default is_active if not provided
            if ($request->has('is_active')) {
                $isActiveValue = $request->input('is_active');
                // Convert string values to boolean
                if (in_array($isActiveValue, ['true', '1', 'TRUE', 'True'])) {
                    $data['is_active'] = true;
                } elseif (in_array($isActiveValue, ['false', '0', 'FALSE', 'False'])) {
                    $data['is_active'] = false;
                } else {
                    $data['is_active'] = (bool)$isActiveValue;
                }
            } else {
                $data['is_active'] = true; // Default to true
            }
            $data['restaurant_id'] = $restaurantId;

            $addon = RestaurantAddon::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Addon created successfully',
                'data' => [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price ? (float)$addon->price : 0,
                    'description' => $addon->description,
                    'image' => $addon->image,
                    'image_url' => $addon->image ? asset('storage/' . $addon->image) : null,
                    'is_active' => $addon->is_active,
                    'restaurant_id' => $addon->restaurant_id,
                    'restaurant' => [
                        'id' => $restaurant->id,
                        'business_name' => $restaurant->business_name,
                        'legal_name' => $restaurant->legal_name
                    ],
                    'created_at' => $addon->created_at,
                    'updated_at' => $addon->updated_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating addon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created addon.
     */
    public function store(Request $request, $restaurantId)
    {
        try {
            $restaurant = Restaurant::findOrFail($restaurantId);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'price' => 'nullable|numeric|min:0',
                'description' => 'nullable|string|max:1000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            $data['restaurant_id'] = $restaurantId;

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('restaurant-addons', 'public');
            }

            // Set default is_active if not provided
            $data['is_active'] = $request->has('is_active') ? (bool)$request->is_active : true;

            $addon = RestaurantAddon::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Addon created successfully',
                'data' => [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price ? (float)$addon->price : 0,
                    'description' => $addon->description,
                    'image' => $addon->image,
                    'image_url' => $addon->image ? asset('storage/' . $addon->image) : null,
                    'is_active' => $addon->is_active,
                    'created_at' => $addon->created_at,
                    'updated_at' => $addon->updated_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating addon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified addon.
     */
    public function show($restaurantId, $addonId)
    {
        try {
            $restaurant = Restaurant::findOrFail($restaurantId);
            $addon = RestaurantAddon::where('restaurant_id', $restaurantId)
                ->where('id', $addonId)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Addon retrieved successfully',
                'data' => [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price ? (float)$addon->price : 0,
                    'description' => $addon->description,
                    'image' => $addon->image,
                    'image_url' => $addon->image ? asset('storage/' . $addon->image) : null,
                    'is_active' => $addon->is_active,
                    'created_at' => $addon->created_at,
                    'updated_at' => $addon->updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving addon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified addon.
     */
    public function update(Request $request, $restaurantId, $addonId)
    {
        try {
            $restaurant = Restaurant::findOrFail($restaurantId);
            $addon = RestaurantAddon::where('restaurant_id', $restaurantId)
                ->where('id', $addonId)
                ->firstOrFail();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'price' => 'nullable|numeric|min:0',
                'description' => 'nullable|string|max:1000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($addon->image) {
                    Storage::disk('public')->delete($addon->image);
                }
                $data['image'] = $request->file('image')->store('restaurant-addons', 'public');
            }

            $addon->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Addon updated successfully',
                'data' => [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price ? (float)$addon->price : 0,
                    'description' => $addon->description,
                    'image' => $addon->image,
                    'image_url' => $addon->image ? asset('storage/' . $addon->image) : null,
                    'is_active' => $addon->is_active,
                    'created_at' => $addon->created_at,
                    'updated_at' => $addon->updated_at,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating addon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified addon.
     */
    public function destroy($restaurantId, $addonId)
    {
        try {
            $restaurant = Restaurant::findOrFail($restaurantId);
            $addon = RestaurantAddon::where('restaurant_id', $restaurantId)
                ->where('id', $addonId)
                ->firstOrFail();

            // Delete image if exists
            if ($addon->image) {
                Storage::disk('public')->delete($addon->image);
            }

            $addon->delete();

            return response()->json([
                'success' => true,
                'message' => 'Addon deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting addon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle addon active status.
     */
    public function toggle($restaurantId, $addonId)
    {
        try {
            $restaurant = Restaurant::findOrFail($restaurantId);
            $addon = RestaurantAddon::where('restaurant_id', $restaurantId)
                ->where('id', $addonId)
                ->firstOrFail();

            $addon->update(['is_active' => !$addon->is_active]);

            $status = $addon->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Addon {$status} successfully",
                'data' => [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'is_active' => $addon->is_active,
                    'updated_at' => $addon->updated_at,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling addon status: ' . $e->getMessage()
            ], 500);
        }
    }
}
