<?php

namespace App\Http\Controllers;

use App\Models\ColdDrinksAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ColdDrinksAddonController extends Controller
{
    public function index()
    {
        $coldDrinksAddons = ColdDrinksAddon::orderBy('name')->get();
        return view('settings.cold-drinks-addons', compact('coldDrinksAddons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cold-drinks-addons', 'public');
        }

        ColdDrinksAddon::create($data);

        return redirect()->route('settings.cold-drinks-addons.index')
            ->with('success', 'Cold drink addon added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $coldDrinksAddon = ColdDrinksAddon::findOrFail($id);

        $data = [
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($coldDrinksAddon->image) {
                Storage::disk('public')->delete($coldDrinksAddon->image);
            }
            $data['image'] = $request->file('image')->store('cold-drinks-addons', 'public');
        }

        $coldDrinksAddon->update($data);

        return redirect()->route('settings.cold-drinks-addons.index')
            ->with('success', 'Cold drink addon updated successfully!');
    }

    public function destroy($id)
    {
        $coldDrinksAddon = ColdDrinksAddon::findOrFail($id);
        
        // Delete image if exists
        if ($coldDrinksAddon->image) {
            Storage::disk('public')->delete($coldDrinksAddon->image);
        }
        
        $coldDrinksAddon->delete();

        return redirect()->route('settings.cold-drinks-addons.index')
            ->with('success', 'Cold drink addon deleted successfully!');
    }

    public function toggle($id)
    {
        $coldDrinksAddon = ColdDrinksAddon::findOrFail($id);
        $coldDrinksAddon->update(['is_active' => !$coldDrinksAddon->is_active]);

        $status = $coldDrinksAddon->is_active ? 'activated' : 'deactivated';
        return redirect()->route('settings.cold-drinks-addons.index')
            ->with('success', "Cold drink addon {$status} successfully!");
    }
}