<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateType;
use App\Models\IssuingAuthority;
use App\Models\ColdDrinksAddon;
use App\Models\MenuCategory;
use App\Models\SecondFlavor;
use App\Models\Slider;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $certificateTypes = CertificateType::orderBy('name')->get();
        $issuingAuthorities = IssuingAuthority::orderBy('name')->get();
        $coldDrinksAddons = ColdDrinksAddon::orderBy('name')->get();
        $menuCategories = MenuCategory::orderBy('name')->get();
        $secondFlavors = SecondFlavor::orderBy('name')->get();
        $sliders = Slider::orderBy('order')->orderBy('created_at')->get();
        
        // Get the active tab from URL parameter, default to 'certificate-types'
        $activeTab = $request->get('tab', 'certificate-types');
        
        return view('settings.index', compact('certificateTypes', 'issuingAuthorities', 'coldDrinksAddons', 'menuCategories', 'secondFlavors', 'sliders', 'activeTab'));
    }

    // Certificate Type Methods
    public function storeCertificateType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:certificate_types,name',
            'description' => 'nullable|string|max:500',
        ]);

        CertificateType::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('settings.index', ['tab' => 'certificate-types'])->with('success', 'Certificate type added successfully!');
    }

    public function updateCertificateType(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:certificate_types,name,' . $id,
            'description' => 'nullable|string|max:500',
        ]);

        $certificateType = CertificateType::findOrFail($id);
        $certificateType->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('settings.index', ['tab' => 'certificate-types'])->with('success', 'Certificate type updated successfully!');
    }

    public function deleteCertificateType($id)
    {
        $certificateType = CertificateType::findOrFail($id);
        $certificateType->delete();

        return redirect()->route('settings.index', ['tab' => 'certificate-types'])->with('success', 'Certificate type deleted successfully!');
    }

    public function toggleCertificateType($id)
    {
        $certificateType = CertificateType::findOrFail($id);
        $certificateType->update([
            'is_active' => !$certificateType->is_active,
        ]);

        return redirect()->route('settings.index', ['tab' => 'certificate-types'])->with('success', 'Certificate type status updated!');
    }

    // Issuing Authority Methods
    public function storeIssuingAuthority(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:issuing_authorities,name',
            'description' => 'nullable|string|max:500',
        ]);

        IssuingAuthority::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('settings.index', ['tab' => 'issuing-authorities'])->with('success', 'Issuing authority added successfully!');
    }

    public function updateIssuingAuthority(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:issuing_authorities,name,' . $id,
            'description' => 'nullable|string|max:500',
        ]);

        $issuingAuthority = IssuingAuthority::findOrFail($id);
        $issuingAuthority->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('settings.index', ['tab' => 'issuing-authorities'])->with('success', 'Issuing authority updated successfully!');
    }

    public function deleteIssuingAuthority($id)
    {
        $issuingAuthority = IssuingAuthority::findOrFail($id);
        $issuingAuthority->delete();

        return redirect()->route('settings.index', ['tab' => 'issuing-authorities'])->with('success', 'Issuing authority deleted successfully!');
    }

    public function toggleIssuingAuthority($id)
    {
        $issuingAuthority = IssuingAuthority::findOrFail($id);
        $issuingAuthority->update([
            'is_active' => !$issuingAuthority->is_active,
        ]);

        return redirect()->route('settings.index', ['tab' => 'issuing-authorities'])->with('success', 'Issuing authority status updated!');
    }

    // Cold Drinks Addons Methods
    public function storeColdDrinksAddon(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cold_drinks_addons,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'is_active' => true,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cold-drinks-addons', 'public');
        }

        ColdDrinksAddon::create($data);

        return redirect()->route('settings.index', ['tab' => 'cold-drinks-addons'])->with('success', 'Cold drink addon added successfully!');
    }

    public function updateColdDrinksAddon(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cold_drinks_addons,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $coldDrinksAddon = ColdDrinksAddon::findOrFail($id);
        
        $data = [
            'name' => $request->name,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($coldDrinksAddon->image) {
                \Storage::disk('public')->delete($coldDrinksAddon->image);
            }
            $data['image'] = $request->file('image')->store('cold-drinks-addons', 'public');
        }

        $coldDrinksAddon->update($data);

        return redirect()->route('settings.index', ['tab' => 'cold-drinks-addons'])->with('success', 'Cold drink addon updated successfully!');
    }

    public function deleteColdDrinksAddon($id)
    {
        $coldDrinksAddon = ColdDrinksAddon::findOrFail($id);
        
        // Delete image if exists
        if ($coldDrinksAddon->image) {
            \Storage::disk('public')->delete($coldDrinksAddon->image);
        }
        
        $coldDrinksAddon->delete();

        return redirect()->route('settings.index', ['tab' => 'cold-drinks-addons'])->with('success', 'Cold drink addon deleted successfully!');
    }

    public function toggleColdDrinksAddon($id)
    {
        $coldDrinksAddon = ColdDrinksAddon::findOrFail($id);
        $coldDrinksAddon->update([
            'is_active' => !$coldDrinksAddon->is_active,
        ]);

        return redirect()->route('settings.index', ['tab' => 'cold-drinks-addons'])->with('success', 'Cold drink addon status updated!');
    }

    // Menu Category Methods
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menu_categories,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'is_active' => true,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu-categories', 'public');
        }

        MenuCategory::create($data);

        return redirect()->route('settings.index', ['tab' => 'restaurant-categories'])->with('success', 'Category added successfully!');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menu_categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = MenuCategory::findOrFail($id);
        
        $data = [
            'name' => $request->name,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('menu-categories', 'public');
        }

        $category->update($data);

        return redirect()->route('settings.index', ['tab' => 'restaurant-categories'])->with('success', 'Category updated successfully!');
    }

    public function deleteCategory($id)
    {
        $category = MenuCategory::findOrFail($id);
        
        // Delete image if exists
        if ($category->image) {
            \Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();

        return redirect()->route('settings.index', ['tab' => 'restaurant-categories'])->with('success', 'Category deleted successfully!');
    }

    public function toggleCategory($id)
    {
        $category = MenuCategory::findOrFail($id);
        $category->update([
            'is_active' => !$category->is_active,
        ]);

        return redirect()->route('settings.index', ['tab' => 'restaurant-categories'])->with('success', 'Category status updated!');
    }

    // Second Flavor Methods
    public function storeSecondFlavor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:second_flavors,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'is_active' => true,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('second-flavors', 'public');
        }

        SecondFlavor::create($data);

        return redirect()->route('settings.index', ['tab' => 'second-flavor'])->with('success', 'Second flavor added successfully!');
    }

    public function updateSecondFlavor(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:second_flavors,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $flavor = SecondFlavor::findOrFail($id);
        
        $data = [
            'name' => $request->name,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($flavor->image) {
                \Storage::disk('public')->delete($flavor->image);
            }
            $data['image'] = $request->file('image')->store('second-flavors', 'public');
        }

        $flavor->update($data);

        return redirect()->route('settings.index', ['tab' => 'second-flavor'])->with('success', 'Second flavor updated successfully!');
    }

    public function deleteSecondFlavor($id)
    {
        $flavor = SecondFlavor::findOrFail($id);
        
        // Delete image if exists
        if ($flavor->image) {
            \Storage::disk('public')->delete($flavor->image);
        }
        
        $flavor->delete();

        return redirect()->route('settings.index', ['tab' => 'second-flavor'])->with('success', 'Second flavor deleted successfully!');
    }

    public function toggleSecondFlavor($id)
    {
        $flavor = SecondFlavor::findOrFail($id);
        $flavor->update([
            'is_active' => !$flavor->is_active,
        ]);

        return redirect()->route('settings.index', ['tab' => 'second-flavor'])->with('success', 'Second flavor status updated!');
    }

    // Slider Management Methods
    public function storeSlider(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => true,
            'order' => Slider::max('order') + 1,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        Slider::create($data);

        return redirect()->route('settings.index', ['tab' => 'slider'])->with('success', 'Slider created successfully!');
    }

    public function updateSlider(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slider = Slider::findOrFail($id);
        $data = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image) {
                \Storage::disk('public')->delete($slider->image);
            }
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($data);

        return redirect()->route('settings.index', ['tab' => 'slider'])->with('success', 'Slider updated successfully!');
    }

    public function destroySlider($id)
    {
        $slider = Slider::findOrFail($id);
        
        // Delete image
        if ($slider->image) {
            \Storage::disk('public')->delete($slider->image);
        }
        
        $slider->delete();

        return redirect()->route('settings.index', ['tab' => 'slider'])->with('success', 'Slider deleted successfully!');
    }

    public function toggleSlider($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->update([
            'is_active' => !$slider->is_active,
        ]);

        return redirect()->route('settings.index', ['tab' => 'slider'])->with('success', 'Slider status updated!');
    }

}
