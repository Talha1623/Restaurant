<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateType;
use App\Models\IssuingAuthority;
use App\Models\ColdDrinksAddon;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $certificateTypes = CertificateType::orderBy('name')->get();
        $issuingAuthorities = IssuingAuthority::orderBy('name')->get();
        $coldDrinksAddons = ColdDrinksAddon::orderBy('name')->get();
        
        // Get the active tab from URL parameter, default to 'certificate-types'
        $activeTab = $request->get('tab', 'certificate-types');
        
        return view('settings.index', compact('certificateTypes', 'issuingAuthorities', 'coldDrinksAddons', 'activeTab'));
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

}
