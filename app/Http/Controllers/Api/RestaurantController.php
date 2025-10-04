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
