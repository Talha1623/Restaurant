<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\RestaurantAddon;
use App\Models\Ingredient;
use App\Models\Category;
use App\Models\MenuCategory;
use App\Models\SecondFlavor;
use App\Models\MenuProperty;
use App\Models\MenuOption;
use App\Models\MenuImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $restaurantId = $request->get('restaurant_id');
        
        $query = Menu::with(['restaurant', 'category', 'properties', 'options', 'images']);
        
        if ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        }
        
        $menus = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get all ingredients for the dropdown
        $ingredients = Ingredient::orderBy('name')->get();
        
        // Get all categories for the dropdown
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $restaurantAddons = RestaurantAddon::where('restaurant_id', $restaurantId)->where('is_active', true)->get();
        
        return view('menus.index', compact('menus', 'restaurantId', 'ingredients', 'categories', 'restaurantAddons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $restaurantId = $request->get('restaurant_id');
        $restaurant = null;
        
        if ($restaurantId) {
            $restaurant = Restaurant::findOrFail($restaurantId);
        }
        
        // Get global menu categories from settings
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Get global second flavors from settings
        $secondFlavors = SecondFlavor::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $restaurantAddons = RestaurantAddon::where('restaurant_id', $restaurantId)->where('is_active', true)->get();
        
        return view('menus.create', compact('restaurant', 'restaurantId', 'categories', 'secondFlavors', 'restaurantAddons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'vat_price' => 'nullable|numeric|min:0',
            'currency' => 'required|string|in:GBP,USD,EUR,PKR',
            'category' => 'required|string|max:255',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'restaurant_id' => 'required|exists:restaurants,id'
        ]);

        $data = $request->all();
        
        // Handle availability checkbox
        $data['is_available'] = $request->has('is_available') ? true : false;
        
        // Handle tags - convert comma-separated string to array
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_filter($tags); // Remove empty values
        } else {
            $data['tags'] = null;
        }
        
        // Handle dietary flags - convert comma-separated string to array
        if ($request->filled('dietary_flags')) {
            $dietaryFlags = array_map('trim', explode(',', $request->dietary_flags));
            $data['dietary_flags'] = array_filter($dietaryFlags); // Remove empty values
        } else {
            $data['dietary_flags'] = null;
        }
        
        // Handle cold drinks addons
        $data['cold_drinks_addons'] = $request->cold_drinks_addons ?? [];
        
        // Handle multiple image uploads
        $menu = Menu::create($data);
        
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

        return redirect()->route('menus.index', ['restaurant_id' => $request->restaurant_id])
            ->with('success', 'Menu item added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        $menu->load('secondFlavor');
        return view('menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        // Get global menu categories from settings
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Get global second flavors from settings
        $secondFlavors = SecondFlavor::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $restaurantAddons = RestaurantAddon::where('restaurant_id', $menu->restaurant_id)->where('is_active', true)->get();
            
        return view('menus.edit', compact('menu', 'categories', 'secondFlavors', 'restaurantAddons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'vat_price' => 'nullable|numeric|min:0',
            'currency' => 'required|string|in:GBP,USD,EUR,PKR',
            'category' => 'required|string|max:255',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'is_available' => 'boolean',
            'spice_level' => 'nullable|integer|min:0|max:5',
            'preparation_time' => 'nullable|integer|min:0|max:300',
            'calories' => 'nullable|integer|min:0|max:5000',
            'tags' => 'nullable|string',
            'allergen' => 'nullable|string|max:255',
            'dietary_flags' => 'nullable|string',
            'cold_drinks_addons' => 'nullable|array',
            'cold_drinks_addons.*' => 'integer|exists:restaurant_addons,id'
        ]);

        $data = $request->all();
        
        // Handle availability checkbox
        $data['is_available'] = $request->has('is_available') ? true : false;
        
        // Handle tags - convert comma-separated string to array
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_filter($tags); // Remove empty values
        } else {
            $data['tags'] = null;
        }
        
        // Handle dietary flags - convert comma-separated string to array
        if ($request->filled('dietary_flags')) {
            $dietaryFlags = array_map('trim', explode(',', $request->dietary_flags));
            $data['dietary_flags'] = array_filter($dietaryFlags); // Remove empty values
        } else {
            $data['dietary_flags'] = null;
        }
        
        // Handle cold drinks addons
        $data['cold_drinks_addons'] = $request->cold_drinks_addons ?? [];
        
        $menu->update($data);
        
        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('menu-images', 'public');
                $menu->images()->create([
                    'image_url' => $imagePath,
                    'is_primary' => false, // New images are not primary by default
                    'sort_order' => $menu->images()->max('sort_order') + $index + 1
                ]);
            }
        }

        return redirect()->route('menus.index', ['restaurant_id' => $menu->restaurant_id])
            ->with('success', 'Menu item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        // Delete image if exists
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        
        $restaurantId = $menu->restaurant_id;
        $menu->delete();

        return redirect()->route('menus.index', ['restaurant_id' => $restaurantId])
            ->with('success', 'Menu item deleted successfully!');
    }
}
