<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    /**
     * Get all restaurants
     */
    public function index(Request $request)
    {
        $query = Restaurant::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('legal_name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by city
        if ($request->has('city')) {
            $query->where('city', 'like', "%{$request->get('city')}%");
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $restaurants = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $restaurants
        ]);
    }

    /**
     * Create a new restaurant
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'legal_name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:restaurants,email',
            'password' => 'required|string|min:6',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'min_order' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'cuisine_tags' => 'nullable|string|max:255',
            'delivery_zone' => 'nullable|string|max:50',
            'delivery_postcode' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Handle file uploads
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('restaurant-logos', 'public');
        }
        
        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('restaurant-banners', 'public');
        }

        // Hash password
        $data['restaurant_password'] = Hash::make($data['password']);
        unset($data['password']);

        $restaurant = Restaurant::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Restaurant created successfully',
            'data' => [
                'restaurant' => $this->formatRestaurant($restaurant)
            ]
        ], 201);
    }

    /**
     * Simple test method
     */
    public function getCompleteDetails(Request $request)
    {
        try {
            // Validate the incoming request to ensure 'restaurant_id' is present
            $validator = Validator::make($request->all(), [
                'restaurant_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Retrieve the restaurant ID from the request body
            $restaurantId = $request->input('restaurant_id');
            $restaurant = Restaurant::find($restaurantId);

            if (!$restaurant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Restaurant not found',
                    'error' => "Restaurant with ID {$restaurantId} does not exist in the database"
                ], 404);
            }

            // STEP 3: Add menus + certificates (with debug)
            $menus = \App\Models\Menu::where('restaurant_id', $restaurantId)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($menu) {
                    return [
                        'id' => $menu->id,
                        'name' => $menu->name,
                        'description' => $menu->description,
                        'price' => $menu->price,
                        'vat_price' => $menu->vat_price,
                        'currency' => $menu->currency,
                        'status' => $menu->status,
                        'is_available' => $menu->is_available,
                        'image_url' => $menu->image ? asset('storage/' . $menu->image) : null,
                        'created_at' => $menu->created_at
                    ];
                });

            // Debug certificates query (FIXED - Safe asset() handling)
            $certificates = [];
            try {
                \Log::info('Starting certificates query for restaurant_id: ' . $restaurantId);
                
                $certificates = \App\Models\Certificate::where('restaurant_id', $restaurantId)
                    ->where('status', 'active')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function($certificate) {
                        return [
                            'id' => $certificate->id,
                            'name' => $certificate->name,
                            'type' => $certificate->type,
                            'issue_date' => $certificate->issue_date,
                            'expiry_date' => $certificate->expiry_date,
                            'issuing_authority' => $certificate->issuing_authority,
                            'certificate_file_url' => $certificate->certificate_file && is_string($certificate->certificate_file)
                                ? asset('storage/' . ltrim($certificate->certificate_file, '/'))
                                : null,
                            'created_at' => $certificate->created_at
                        ];
                    });
                    
                \Log::info('Certificates query successful, count: ' . $certificates->count());
                
            } catch (\Exception $e) {
                \Log::error('Certificates query failed: ' . $e->getMessage());
                \Log::error('Certificates query error file: ' . $e->getFile() . ' line: ' . $e->getLine());
                $certificates = collect([]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Restaurant details retrieved successfully',
                'data' => [
                    'restaurant' => $this->formatRestaurant($restaurant),
                    'menus' => $menus,
                    'certificates' => $certificates
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve restaurant details',
                'error' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Get a specific restaurant with ID in request body
     */
    public function showWithIdInBody(Request $request)
    {
        try {
            // Validate the incoming request to ensure 'id' is present
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Retrieve the restaurant ID from the request body
            $restaurantId = $request->input('id');
            $restaurant = Restaurant::find($restaurantId);

            if (!$restaurant) {
                // Debug: Log all available restaurant IDs
                $allRestaurantIds = Restaurant::pluck('id')->toArray();
                $totalRestaurants = Restaurant::count();
                $latestRestaurant = Restaurant::latest()->first();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Restaurant not found',
                    'error' => "Restaurant with ID {$restaurantId} does not exist in the database",
                    'debug' => [
                        'requested_id' => $restaurantId,
                        'available_ids' => $allRestaurantIds,
                        'total_restaurants' => $totalRestaurants,
                        'latest_restaurant_id' => $latestRestaurant ? $latestRestaurant->id : 'none',
                        'latest_restaurant_name' => $latestRestaurant ? $latestRestaurant->business_name : 'none'
                    ]
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Restaurant retrieved successfully',
                'data' => [
                    'restaurant' => $this->formatRestaurant($restaurant)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve restaurant',
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
     * Get a specific restaurant
     */
    public function show($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'restaurant' => $this->formatRestaurant($restaurant)
            ]
        ]);
    }

    /**
     * Update a restaurant
     */
    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'legal_name' => 'sometimes|required|string|max:255',
            'business_name' => 'sometimes|required|string|max:255',
            'address_line1' => 'sometimes|required|string|max:500',
            'city' => 'sometimes|required|string|max:100',
            'postcode' => 'sometimes|required|string|max:20',
            'phone' => 'sometimes|required|string|max:20',
            'contact_person' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:restaurants,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'opening_time' => 'sometimes|required|date_format:H:i',
            'closing_time' => 'sometimes|required|date_format:H:i',
            'min_order' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|in:active,inactive',
            'cuisine_tags' => 'nullable|string|max:255',
            'delivery_zone' => 'nullable|string|max:50',
            'delivery_postcode' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Handle file uploads
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($restaurant->logo) {
                Storage::disk('public')->delete($restaurant->logo);
            }
            $data['logo'] = $request->file('logo')->store('restaurant-logos', 'public');
        }
        
        if ($request->hasFile('banner')) {
            // Delete old banner
            if ($restaurant->banner) {
                Storage::disk('public')->delete($restaurant->banner);
            }
            $data['banner'] = $request->file('banner')->store('restaurant-banners', 'public');
        }

        // Hash password if provided
        if (isset($data['password'])) {
            $data['restaurant_password'] = Hash::make($data['password']);
            unset($data['password']);
        }

        $restaurant->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Restaurant updated successfully',
            'data' => [
                'restaurant' => $this->formatRestaurant($restaurant->fresh())
            ]
        ]);
    }

    /**
     * Delete a restaurant
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found'
            ], 404);
        }

        // Delete associated files
        if ($restaurant->logo) {
            Storage::disk('public')->delete($restaurant->logo);
        }
        if ($restaurant->banner) {
            Storage::disk('public')->delete($restaurant->banner);
        }

        $restaurant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Restaurant deleted successfully'
        ]);
    }

    /**
     * Toggle restaurant status
     */
    public function toggleStatus($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found'
            ], 404);
        }

        $restaurant->status = $restaurant->status === 'active' ? 'inactive' : 'active';
        $restaurant->save();

        return response()->json([
            'success' => true,
            'message' => 'Restaurant status updated successfully',
            'data' => [
                'restaurant' => $this->formatRestaurant($restaurant)
            ]
        ]);
    }

    /**
     * Toggle restaurant blocked status
     */
    public function toggleBlock($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found'
            ], 404);
        }

        $restaurant->blocked = !$restaurant->blocked;
        $restaurant->save();

        return response()->json([
            'success' => true,
            'message' => 'Restaurant block status updated successfully',
            'data' => [
                'restaurant' => $this->formatRestaurant($restaurant)
            ]
        ]);
    }

    /**
     * Format restaurant data for API response
     */
    private function formatRestaurant($restaurant)
    {
        return [
            'id' => $restaurant->id,
            'legal_name' => $restaurant->legal_name,
            'business_name' => $restaurant->business_name,
            'address_line1' => $restaurant->address_line1,
            'city' => $restaurant->city,
            'postcode' => $restaurant->postcode,
            'phone' => $restaurant->phone,
            'contact_person' => $restaurant->contact_person,
            'email' => $restaurant->email,
            'opening_time' => $restaurant->opening_time,
            'closing_time' => $restaurant->closing_time,
            'min_order' => $restaurant->min_order,
            'status' => $restaurant->status,
            'blocked' => $restaurant->blocked,
            'cuisine_tags' => $restaurant->cuisine_tags,
            'delivery_zone' => $restaurant->delivery_zone,
            'delivery_postcode' => $restaurant->delivery_postcode,
            'logo' => $restaurant->logo ? asset('storage/' . $restaurant->logo) : null,
            'banner' => $restaurant->banner ? asset('storage/' . $restaurant->banner) : null,
            'created_at' => $restaurant->created_at,
            'updated_at' => $restaurant->updated_at,
        ];
    }
}
