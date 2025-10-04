<?php

namespace App\Http\Controllers;

use App\Models\RestaurantAddon;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantAddonController extends Controller
{
    public function index($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $addons = $restaurant->addons()->orderBy('name')->get();
        
        return view('restaurants.addons.index', compact('restaurant', 'addons'));
    }

    public function create($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        return view('restaurants.addons.create', compact('restaurant'));
    }

    public function store(Request $request, $restaurantId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->all();
            $data['restaurant_id'] = $restaurantId;

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('restaurant-addons', 'public');
            }

            RestaurantAddon::create($data);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Addon created successfully!']);
            }

            // Check if redirect should be to menu page
            if ($request->input('redirect_to') === 'menu') {
                return redirect()->route('menus.index', ['restaurant_id' => $restaurantId])
                    ->with('success', 'Addon created successfully!');
            }

            return redirect()->route('restaurants.addons.index', $restaurantId)
                ->with('success', 'Addon created successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error creating addon: ' . $e->getMessage()], 500);
            }

            return redirect()->back()
                ->with('error', 'Error creating addon: ' . $e->getMessage());
        }
    }

    public function edit($restaurantId, $addonId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $addon = $restaurant->addons()->findOrFail($addonId);
        
        return view('restaurants.addons.edit', compact('restaurant', 'addon'));
    }

    public function update(Request $request, $restaurantId, $addonId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $addon = $restaurant->addons()->findOrFail($addonId);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($addon->image) {
                Storage::disk('public')->delete($addon->image);
            }
            $data['image'] = $request->file('image')->store('restaurant-addons', 'public');
        }

        $addon->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Addon updated successfully!']);
        }

        // Check if redirect should be to menu page
        if ($request->input('redirect_to') === 'menu') {
            return redirect()->route('menus.index', ['restaurant_id' => $restaurantId])
                ->with('success', 'Addon updated successfully!');
        }

        return redirect()->route('restaurants.addons.index', $restaurantId)
            ->with('success', 'Addon updated successfully!');
    }

    public function destroy($restaurantId, $addonId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $addon = $restaurant->addons()->findOrFail($addonId);

        // Delete image if exists
        if ($addon->image) {
            Storage::disk('public')->delete($addon->image);
        }

        $addon->delete();

        // Check if request came from menu page
        if (request()->header('referer') && str_contains(request()->header('referer'), 'menus')) {
            return redirect()->route('menus.index', ['restaurant_id' => $restaurantId])
                ->with('success', 'Addon deleted successfully!');
        }

        return redirect()->route('restaurants.addons.index', $restaurantId)
            ->with('success', 'Addon deleted successfully!');
    }

    public function toggle($restaurantId, $addonId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $addon = $restaurant->addons()->findOrFail($addonId);

        $addon->update(['is_active' => !$addon->is_active]);

        // Check if request came from menu page
        if (request()->header('referer') && str_contains(request()->header('referer'), 'menus')) {
            return redirect()->route('menus.index', ['restaurant_id' => $restaurantId])
                ->with('success', 'Addon status updated successfully!');
        }

        return redirect()->route('restaurants.addons.index', $restaurantId)
            ->with('success', 'Addon status updated successfully!');
    }
}