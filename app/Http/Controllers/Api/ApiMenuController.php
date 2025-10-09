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
                ->get(['id', 'name', 'price', 'description', 'image']);
            
            // Format addons with full image URL
            $formattedAddons = $addons->map(function($addon) {
                return [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price ? (float)$addon->price : 0,
                    'description' => $addon->description,
                    'image' => $addon->image,
                    'image_url' => $addon->image ? asset('storage/' . $addon->image) : null
                ];
            });
            
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
                    'addons' => $formattedAddons,
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
                ->with(['images', 'category', 'secondFlavor'])
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
                'category_id' => 'required|integer|exists:menu_categories,id',
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
                ->with(['images', 'restaurant', 'category', 'secondFlavor'])
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
                'category_id' => 'sometimes|required|integer|exists:menu_categories,id',
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

    /**
     * Store menu without restaurant_id in URL
     */
    public function storeWithoutRestaurantId(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'restaurant_id' => 'required|integer|exists:restaurants,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'ingredients' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'vat_price' => 'nullable|numeric|min:0',
                'currency' => 'required|string|in:GBP,USD,EUR,PKR',
                'category_id' => 'required|integer|exists:menu_categories,id',
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

            $restaurantId = $request->restaurant_id;
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
     * Get menus without restaurant_id in URL
     */
    public function indexWithoutRestaurantId(Request $request)
    {
        try {
            $query = Menu::with(['images', 'restaurant', 'category', 'secondFlavor']);

            // Auto-detect restaurant_id from authenticated user
            $user = $request->user();
            $restaurantId = $request->restaurant_id ?? $user->id;
            
            // Ensure we have a valid restaurant ID
            if (!$restaurantId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Restaurant ID not found. Please provide restaurant_id parameter.'
                ], 400);
            }
            
            $query->where('restaurant_id', $restaurantId);

            // Search filter
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Status filter
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Category filter
            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $menus = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Menus retrieved successfully',
                'data' => [
                    'menus' => $menus->items(),
                    'pagination' => [
                        'current_page' => $menus->currentPage(),
                        'last_page' => $menus->lastPage(),
                        'per_page' => $menus->perPage(),
                        'total' => $menus->total(),
                        'from' => $menus->firstItem(),
                        'to' => $menus->lastItem()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve menus',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show menu without restaurant_id in URL
     */
    public function showWithoutRestaurantId($menuId)
    {
        try {
            $menu = Menu::where('id', $menuId)
                ->with(['images', 'restaurant', 'category', 'secondFlavor'])
                ->firstOrFail();
            
            return response()->json([
                'success' => true,
                'message' => 'Menu item retrieved successfully',
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
     * Update menu without restaurant_id in URL
     */
    public function updateWithoutRestaurantId(Request $request, $menuId)
    {
        try {
            $menu = Menu::where('id', $menuId)->firstOrFail();

            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'ingredients' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'vat_price' => 'nullable|numeric|min:0',
                'currency' => 'sometimes|required|string|in:GBP,USD,EUR,PKR',
                'category_id' => 'sometimes|required|integer|exists:menu_categories,id',
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
            
            // Handle image uploads
            if ($request->hasFile('images')) {
                // Delete existing images
                foreach ($menu->images as $image) {
                    Storage::disk('public')->delete($image->image_url);
                    $image->delete();
                }
                
                // Upload new images
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('menu-images', 'public');
                    $menu->images()->create([
                        'image_url' => $imagePath,
                        'is_primary' => $index === 0,
                        'sort_order' => $index + 1
                    ]);
                }
            }

            $menu->update($data);
            
            // Load relationships for response
            $menu->load(['images', 'restaurant', 'category', 'secondFlavor']);

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
     * Update menu with ID in request body (like delete)
     */
    public function updateWithIdInBody(Request $request)
    {
        try {
            // Debug: Check what data is being received
            $requestData = $request->all();
            
            // Validate the incoming request to ensure 'id' is present
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'ingredients' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'vat_price' => 'nullable|numeric|min:0',
                'currency' => 'sometimes|required|string|in:GBP,USD,EUR,PKR',
                'category_id' => 'sometimes|required|integer|exists:menu_categories,id',
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
                    'errors' => $validator->errors(),
                    'debug_request_data' => $requestData
                ], 422);
            }

            // Retrieve the menu by ID from the request body
            $menuId = $request->input('id');
            
            // Debug: Log what ID we're actually receiving
            \Log::info('Menu Update Request', [
                'received_id' => $menuId,
                'request_all' => $request->all(),
                'content_type' => $request->header('Content-Type')
            ]);
            
            // Debug: Log all available menu IDs and database info
            $allMenuIds = Menu::pluck('id')->toArray();
            $totalMenus = Menu::count();
            $latestMenu = Menu::latest()->first();
            
            // Debug: Check database connection
            \Log::info('Database Info', [
                'db_connection' => \DB::connection()->getDatabaseName(),
                'db_driver' => config('database.default')
            ]);
            
            // Check if menu exists
            $menu = Menu::find($menuId);
            
            if (!$menu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu item not found',
                    'error' => "Menu with ID {$menuId} does not exist in the database",
                    'debug' => [
                        'requested_id' => $menuId,
                        'available_ids' => $allMenuIds,
                        'total_menus' => $totalMenus,
                        'latest_menu_id' => $latestMenu ? $latestMenu->id : 'none',
                        'latest_menu_name' => $latestMenu ? $latestMenu->name : 'none'
                    ]
                ], 404);
            }
            
            // Optional: Check if menu belongs to authenticated restaurant (if needed)
            // For now, we'll allow any authenticated user to update any menu
            // You can uncomment this if you want to enforce restaurant ownership
            /*
            $user = auth()->user();
            if ($user && $menu->restaurant_id != $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu item not found or access denied'
                ], 404);
            }
            */

            $data = $request->except('id'); // Exclude 'id' from update data
            
            // Handle availability checkbox
            if ($request->has('is_available')) {
                $data['is_available'] = $request->is_available ? true : false;
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
            
            // Handle image uploads
            if ($request->hasFile('images')) {
                // Delete existing images
                foreach ($menu->images as $image) {
                    if (Storage::disk('public')->exists($image->image_url)) {
                        Storage::disk('public')->delete($image->image_url);
                    }
                    $image->delete();
                }
                
                // Upload new images
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('menu-images', 'public');
                    $menu->images()->create([
                        'image_url' => $imagePath,
                        'is_primary' => $index === 0,
                        'sort_order' => $index + 1
                    ]);
                }
            }

            // Update the menu
            $menu->update($data);
            
            // Load relationships for response
            $menu->load(['images', 'restaurant', 'category', 'secondFlavor']);

            return response()->json([
                'success' => true,
                'message' => 'Menu item updated successfully',
                'data' => $menu
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $ve->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu item',
                'error' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }

    /**
     * View menu with ID in request body
     */
    public function viewWithIdInBody(Request $request)
    {
        try {
            // Debug: Check what data is being received
            $requestData = $request->all();
            
            // Validate the incoming request to ensure 'id' is present
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'debug_request_data' => $requestData
                ], 422);
            }

            // Retrieve the menu by ID from the request body
            $menuId = $request->input('id');
            
            // Debug: Log what ID we're actually receiving
            \Log::info('Menu View Request', [
                'received_id' => $menuId,
                'request_all' => $request->all(),
                'content_type' => $request->header('Content-Type')
            ]);
            
            // Check if menu exists
            $menu = Menu::with(['images', 'restaurant', 'category', 'secondFlavor'])->find($menuId);
            
            if (!$menu) {
                // Debug: Log all available menu IDs
                $allMenuIds = Menu::pluck('id')->toArray();
                $totalMenus = Menu::count();
                $latestMenu = Menu::latest()->first();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Menu item not found',
                    'error' => "Menu with ID {$menuId} does not exist in the database",
                    'debug' => [
                        'requested_id' => $menuId,
                        'available_ids' => $allMenuIds,
                        'total_menus' => $totalMenus,
                        'latest_menu_id' => $latestMenu ? $latestMenu->id : 'none',
                        'latest_menu_name' => $latestMenu ? $latestMenu->name : 'none'
                    ]
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Menu item retrieved successfully',
                'data' => $menu
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve menu item',
                'error' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }

    /**
     * Delete menu without restaurant_id in URL
     */
 public function destroyWithoutRestaurantId(Request $request)
{
    try {
        // Validate the incoming request to ensure 'id' is present
        $request->validate([
            'id' => 'required|integer|exists:menus,id'
        ]);

        // Retrieve the menu by ID from the request
        $menuId = $request->input('id');
        $menu = Menu::with('images')->findOrFail($menuId);

        // Delete associated image files and their DB records
        foreach ($menu->images as $image) {
            // Delete the file from storage
            if (Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }

            // Delete the image record from the database
            $image->delete();
        }

        // Delete the menu item
        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu item deleted successfully'
        ]);

    } catch (\Illuminate\Validation\ValidationException $ve) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $ve->errors()
        ], 422);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete menu item',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Get all menus (from all restaurants) - for customers
     * Simple version - just get all menus
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllMenus(Request $request)
    {
        try {
            // Simple query - get all active menus with restaurant info
            $menus = Menu::where('status', 'active')
                ->whereHas('restaurant', function($q) {
                    $q->where('status', 'active');
                })
                ->with(['restaurant', 'category', 'secondFlavor'])
                ->orderBy('price', 'asc')
                ->get();

            // Format response - simple structure
            $formattedMenus = $menus->map(function($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'description' => $menu->description,
                    'price' => $menu->price,
                    'vat_price' => $menu->vat_price,
                    'currency' => $menu->currency ?? 'GBP',
                    'image_url' => $menu->image ? asset('storage/' . $menu->image) : null,
                    'is_available' => $menu->is_available ?? true,
                    'spice_level' => $menu->spice_level ?? 0,
                    'preparation_time' => $menu->preparation_time,
                    'calories' => $menu->calories,
                    'tags' => $menu->tags ?? [],
                    'dietary_flags' => $menu->dietary_flags ?? [],
                    'category' => $menu->category ? [
                        'id' => $menu->category->id,
                        'name' => $menu->category->name,
                    ] : null,
                    'second_flavor' => $menu->secondFlavor ? [
                        'id' => $menu->secondFlavor->id,
                        'name' => $menu->secondFlavor->name,
                    ] : null,
                    'restaurant' => [
                        'id' => $menu->restaurant->id,
                        'business_name' => $menu->restaurant->business_name,
                        'logo' => $menu->restaurant->logo ? asset('storage/' . $menu->restaurant->logo) : null,
                        'city' => $menu->restaurant->city,
                        'phone' => $menu->restaurant->phone,
                        'opening_time' => $menu->restaurant->opening_time,
                        'closing_time' => $menu->restaurant->closing_time,
                    ],
                    'created_at' => $menu->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'All menus retrieved successfully',
                'data' => [
                    'total_menus' => $menus->count(),
                    'menus' => $formattedMenus
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve menus',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all menus by second flavor ID (from all restaurants)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMenusBySecondFlavor(Request $request)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'second_flavor_id' => 'required|integer|exists:second_flavors,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $secondFlavorId = $request->input('second_flavor_id');

            // Get second flavor details
            $secondFlavor = SecondFlavor::find($secondFlavorId);

            if (!$secondFlavor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Second Flavor not found',
                    'error' => "Second Flavor with ID {$secondFlavorId} does not exist"
                ], 404);
            }

            // Get all menus for this second flavor with restaurant details
            $menus = Menu::where('second_flavor_id', $secondFlavorId)
                ->where('status', 'active')
                ->whereHas('restaurant', function($query) {
                    $query->where('status', 'active');
                })
                ->with(['restaurant'])
                ->orderBy('price', 'asc')
                ->get()
                ->map(function($menu) {
                    return [
                        'id' => $menu->id,
                        'name' => $menu->name,
                        'description' => $menu->description,
                        'price' => $menu->price,
                        'vat_price' => $menu->vat_price,
                        'currency' => $menu->currency ?? 'GBP',
                        'image_url' => $menu->image ? asset('storage/' . $menu->image) : null,
                        'is_available' => $menu->is_available ?? true,
                        'spice_level' => $menu->spice_level ?? 0,
                        'preparation_time' => $menu->preparation_time,
                        'calories' => $menu->calories,
                        'tags' => $menu->tags ?? [],
                        'dietary_flags' => $menu->dietary_flags ?? [],
                        'restaurant' => [
                            'id' => $menu->restaurant->id,
                            'business_name' => $menu->restaurant->business_name,
                            'legal_name' => $menu->restaurant->legal_name,
                            'logo' => $menu->restaurant->logo ? asset('storage/' . $menu->restaurant->logo) : null,
                            'city' => $menu->restaurant->city,
                            'phone' => $menu->restaurant->phone,
                            'email' => $menu->restaurant->email,
                            'opening_time' => $menu->restaurant->opening_time,
                            'closing_time' => $menu->restaurant->closing_time,
                            'min_order' => $menu->restaurant->min_order,
                            'delivery_zone' => $menu->restaurant->delivery_zone,
                            'cuisine_tags' => $menu->restaurant->cuisine_tags,
                        ],
                        'created_at' => $menu->created_at,
                    ];
                });

            // Count unique restaurants
            $uniqueRestaurants = $menus->pluck('restaurant.id')->unique()->count();

            return response()->json([
                'success' => true,
                'message' => 'Menus retrieved successfully',
                'data' => [
                    'second_flavor' => [
                        'id' => $secondFlavor->id,
                        'name' => $secondFlavor->name,
                        'image_url' => $secondFlavor->image ? asset('storage/' . $secondFlavor->image) : null,
                    ],
                    'total_menus' => $menus->count(),
                    'total_restaurants' => $uniqueRestaurants,
                    'menus' => $menus
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve menus',
                'error' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Get all menus by category ID (from all restaurants)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMenusByCategory(Request $request)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|integer|exists:menu_categories,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $categoryId = $request->input('category_id');

            // Get category details
            $category = MenuCategory::find($categoryId);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                    'error' => "Category with ID {$categoryId} does not exist"
                ], 404);
            }

            // Get all menus for this category with restaurant details
            $menus = Menu::where('category_id', $categoryId)
                ->where('status', 'active')
                ->whereHas('restaurant', function($query) {
                    $query->where('status', 'active');
                })
                ->with(['restaurant'])
                ->orderBy('price', 'asc')
                ->get()
                ->map(function($menu) {
                    return [
                        'id' => $menu->id,
                        'name' => $menu->name,
                        'description' => $menu->description,
                        'price' => $menu->price,
                        'vat_price' => $menu->vat_price,
                        'currency' => $menu->currency ?? 'GBP',
                        'image_url' => $menu->image ? asset('storage/' . $menu->image) : null,
                        'is_available' => $menu->is_available ?? true,
                        'spice_level' => $menu->spice_level ?? 0,
                        'preparation_time' => $menu->preparation_time,
                        'calories' => $menu->calories,
                        'tags' => $menu->tags ?? [],
                        'dietary_flags' => $menu->dietary_flags ?? [],
                        'restaurant' => [
                            'id' => $menu->restaurant->id,
                            'business_name' => $menu->restaurant->business_name,
                            'legal_name' => $menu->restaurant->legal_name,
                            'logo' => $menu->restaurant->logo ? asset('storage/' . $menu->restaurant->logo) : null,
                            'city' => $menu->restaurant->city,
                            'phone' => $menu->restaurant->phone,
                            'email' => $menu->restaurant->email,
                            'opening_time' => $menu->restaurant->opening_time,
                            'closing_time' => $menu->restaurant->closing_time,
                            'min_order' => $menu->restaurant->min_order,
                            'delivery_zone' => $menu->restaurant->delivery_zone,
                            'cuisine_tags' => $menu->restaurant->cuisine_tags,
                        ],
                        'created_at' => $menu->created_at,
                    ];
                });

            // Count unique restaurants
            $uniqueRestaurants = $menus->pluck('restaurant.id')->unique()->count();

            return response()->json([
                'success' => true,
                'message' => 'Menus retrieved successfully',
                'data' => [
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'description' => $category->description,
                        'image_url' => $category->image ? asset('storage/' . $category->image) : null,
                    ],
                    'total_menus' => $menus->count(),
                    'total_restaurants' => $uniqueRestaurants,
                    'menus' => $menus
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve menus',
                'error' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

}
