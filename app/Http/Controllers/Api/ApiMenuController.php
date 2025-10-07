<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\RestaurantAddon;
use App\Models\MenuCategory;
use App\Models\SecondFlavor;
use App\Models\MenuImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiMenuController extends Controller
{
    /**
     * Get form data for menu creation (categories, addons, static options)
     */
    public function getFormData($restaurantId)
    {
        try {
            $restaurant = Restaurant::findOrFail($restaurantId);
            
            // Get global menu categories from settings
            $categories = MenuCategory::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'description', 'image']);
            
            // Format categories with full image URL
            $formattedCategories = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'image' => $category->image,
                    'image_url' => $category->image ? asset('storage/' . $category->image) : null
                ];
            });
            
            // Get global second flavors from settings
            $secondFlavors = SecondFlavor::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'image']);
            
            // Format second flavors with full image URL
            $formattedSecondFlavors = $secondFlavors->map(function($flavor) {
                return [
                    'id' => $flavor->id,
                    'name' => $flavor->name,
                    'image' => $flavor->image,
                    'image_url' => $flavor->image ? asset('storage/' . $flavor->image) : null
                ];
            });
            
            // Get addons for this restaurant
            $addons = RestaurantAddon::where('restaurant_id', $restaurantId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'image']);
            
            // Static options
            $formOptions = [
                'currencies' => [
                    ['value' => 'GBP', 'label' => 'GBP (£)'],
                    ['value' => 'USD', 'label' => 'USD ($)'],
                    ['value' => 'EUR', 'label' => 'EUR (€)'],
                    ['value' => 'PKR', 'label' => 'PKR (₨)']
                ],
                'status_options' => [
                    ['value' => 'active', 'label' => 'Active'],
                    ['value' => 'inactive', 'label' => 'Inactive']
                ],
                'spice_levels' => [
                    ['value' => 0, 'label' => 'No Spice'],
                    ['value' => 1, 'label' => 'Mild (1⭐)'],
                    ['value' => 2, 'label' => 'Medium (2⭐)'],
                    ['value' => 3, 'label' => 'Hot (3⭐)'],
                    ['value' => 4, 'label' => 'Very Hot (4⭐)'],
                    ['value' => 5, 'label' => 'Extreme (5⭐)']
                ]
            ];
            
            return response()->json([
                'success' => true,
                'data' => [
                    'restaurant' => [
                        'id' => $restaurant->id,
                        'name' => $restaurant->business_name,
                        'legal_name' => $restaurant->legal_name
                    ],
                    'categories' => $formattedCategories,
                    'second_flavors' => $formattedSecondFlavors,
                    'addons' => $addons,
                    'form_options' => $formOptions
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch form data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of menus for a restaurant
     */
    public function index($restaurantId)
    {
        try {
            $menus = Menu::where('restaurant_id', $restaurantId)
                ->with(['images', 'secondFlavor'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            return response()->json([
                'success' => true,
                'data' => $menus
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch menus',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created menu item
     */
    public function store(Request $request, $restaurantId)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'ingredients' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'vat_price' => 'nullable|numeric|min:0',
                'currency' => 'required|string|in:GBP,USD,EUR,PKR',
                'category' => 'required|string|max:255',
                'second_flavor_id' => 'nullable|integer|exists:second_flavors,id',
                'status' => 'required|in:active,inactive',
                'is_available' => 'boolean',
                'spice_level' => 'nullable|integer|min:0|max:5',
                'preparation_time' => 'nullable|integer|min:0|max:300',
                'calories' => 'nullable|integer|min:0|max:5000',
                'tags' => 'nullable|string',
                'allergen' => 'nullable|string|max:255',
                'dietary_flags' => 'nullable|string',
                'cold_drinks_addons' => 'nullable|array',
                'cold_drinks_addons.*' => 'integer|exists:restaurant_addons,id',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
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
            
            // Handle availability checkbox
            $data['is_available'] = $request->has('is_available') ? true : false;
            
            // Handle tags - convert comma-separated string to array
            if ($request->filled('tags')) {
                $tags = array_map('trim', explode(',', $request->tags));
                $data['tags'] = array_filter($tags);
            } else {
                $data['tags'] = null;
            }
            
            // Handle dietary flags - convert comma-separated string to array
            if ($request->filled('dietary_flags')) {
                $dietaryFlags = array_map('trim', explode(',', $request->dietary_flags));
                $data['dietary_flags'] = array_filter($dietaryFlags);
            } else {
                $data['dietary_flags'] = null;
            }
            
            // Handle cold drinks addons
            $data['cold_drinks_addons'] = $request->cold_drinks_addons ?? [];
            
            // Create menu item
            $menu = Menu::create($data);
            
            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('menu-images', 'public');
                    $menu->images()->create([
                        'image_url' => $imagePath,
                        'is_primary' => $index === 0, // First image is primary
                        'sort_order' => $index + 1
                    ]);
                }
            }

            // Load relationships for response
            $menu->load(['images', 'restaurant', 'secondFlavor']);

            return response()->json([
                'success' => true,
                'message' => 'Menu item created successfully',
                'data' => $menu
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create menu item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified menu item
     */
    public function show($restaurantId, $menuId)
    {
        try {
            $menu = Menu::where('restaurant_id', $restaurantId)
                ->where('id', $menuId)
                ->with(['images', 'restaurant', 'secondFlavor'])
                ->firstOrFail();
            
            return response()->json([
                'success' => true,
                'data' => $menu
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified menu item
     */
    public function update(Request $request, $restaurantId, $menuId)
    {
        try {
            $menu = Menu::where('restaurant_id', $restaurantId)
                ->where('id', $menuId)
                ->firstOrFail();

            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'ingredients' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'vat_price' => 'nullable|numeric|min:0',
                'currency' => 'sometimes|required|string|in:GBP,USD,EUR,PKR',
                'category' => 'sometimes|required|string|max:255',
                'second_flavor_id' => 'nullable|integer|exists:second_flavors,id',
                'status' => 'sometimes|required|in:active,inactive',
                'is_available' => 'boolean',
                'spice_level' => 'nullable|integer|min:0|max:5',
                'preparation_time' => 'nullable|integer|min:0|max:300',
                'calories' => 'nullable|integer|min:0|max:5000',
                'tags' => 'nullable|string',
                'allergen' => 'nullable|string|max:255',
                'dietary_flags' => 'nullable|string',
                'cold_drinks_addons' => 'nullable|array',
                'cold_drinks_addons.*' => 'integer|exists:restaurant_addons,id',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            // Handle availability checkbox
            if ($request->has('is_available')) {
                $data['is_available'] = true;
            } elseif ($request->has('is_available') && !$request->is_available) {
                $data['is_available'] = false;
            }
            
            // Handle tags
            if ($request->filled('tags')) {
                $tags = array_map('trim', explode(',', $request->tags));
                $data['tags'] = array_filter($tags);
            }
            
            // Handle dietary flags
            if ($request->filled('dietary_flags')) {
                $dietaryFlags = array_map('trim', explode(',', $request->dietary_flags));
                $data['dietary_flags'] = array_filter($dietaryFlags);
            }
            
            // Handle cold drinks addons
            if ($request->has('cold_drinks_addons')) {
                $data['cold_drinks_addons'] = $request->cold_drinks_addons;
            }
            
            // Update menu item
            $menu->update($data);
            
            // Handle new image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('menu-images', 'public');
                    $menu->images()->create([
                        'image_url' => $imagePath,
                        'is_primary' => false,
                        'sort_order' => $menu->images()->count() + $index + 1
                    ]);
                }
            }

            // Load relationships for response
            $menu->load(['images', 'restaurant']);

            return response()->json([
                'success' => true,
                'message' => 'Menu item updated successfully',
                'data' => $menu
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified menu item
     */
    public function destroy($restaurantId, $menuId)
    {
        try {
            $menu = Menu::where('restaurant_id', $restaurantId)
                ->where('id', $menuId)
                ->firstOrFail();
            
            // Delete associated images from storage
            foreach ($menu->images as $image) {
                if (Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }
            }
            
            // Delete menu item (cascade will delete images)
            $menu->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Menu item deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete menu item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle menu item availability
     */
    public function toggleAvailability($restaurantId, $menuId)
    {
        try {
            $menu = Menu::where('restaurant_id', $restaurantId)
                ->where('id', $menuId)
                ->firstOrFail();
            
            $menu->update(['is_available' => !$menu->is_available]);
            
            return response()->json([
                'success' => true,
                'message' => 'Menu availability updated successfully',
                'data' => [
                    'is_available' => $menu->is_available
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle menu availability',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get menu statistics for a restaurant
     */
    public function getStats($restaurantId)
    {
        try {
            $stats = [
                'total_menus' => Menu::where('restaurant_id', $restaurantId)->count(),
                'active_menus' => Menu::where('restaurant_id', $restaurantId)->where('status', 'active')->count(),
                'available_menus' => Menu::where('restaurant_id', $restaurantId)->where('is_available', true)->count(),
                'categories_count' => Menu::where('restaurant_id', $restaurantId)->distinct('category')->count(),
                'total_addons' => RestaurantAddon::where('restaurant_id', $restaurantId)->where('is_active', true)->count()
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch menu statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
