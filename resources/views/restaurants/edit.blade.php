@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg border border-gray-200">
    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
        <a href="{{ route('restaurants.show', $restaurant) }}" class="hover:text-green-600 transition-colors">
            <i class="fas fa-arrow-left text-green-600 hover:text-green-700"></i>
        </a> Edit Restaurant
    </h2>

    <form method="POST" action="{{ route('restaurants.update',$restaurant) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')

        <div class="space-y-4">
            <!-- Business & Legal Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Legal Name *</label>
                    <input type="text" name="legal_name" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-green-400 text-sm" 
                           value="{{ $restaurant->legal_name ?? '' }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Business Name *</label>
                    <input type="text" name="business_name" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 text-sm" 
                           value="{{ $restaurant->business_name ?? '' }}" required>
                </div>
            </div>

            <!-- Address Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Address Line 1 *</label>
                    <input type="text" name="address_line1" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-400 text-sm" 
                           value="{{ $restaurant->address_line1 ?? '' }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">City *</label>
                    <input type="text" name="city" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-pink-400 text-sm" 
                           value="{{ $restaurant->city ?? '' }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Postcode *</label>
                    <input type="text" name="postcode" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-orange-400 text-sm" 
                           value="{{ $restaurant->postcode ?? '' }}" required>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Phone *</label>
                    <input type="tel" name="phone" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-purple-400 text-sm" 
                           value="{{ $restaurant->phone ?? '' }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Contact Person *</label>
                    <input type="text" name="contact_person" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 text-sm" 
                           value="{{ $restaurant->contact_person ?? '' }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-400 text-sm" 
                           value="{{ $restaurant->email ?? '' }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-purple-400 text-sm" 
                           placeholder="Leave blank to keep current password">
                </div>
            </div>

            <!-- Hours & Order Settings -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Opening Hours *</label>
                    <input type="time" name="opening_time" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-green-400 text-sm" 
                           value="{{ $restaurant->opening_time ?? '' }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Closing Hours *</label>
                    <input type="time" name="closing_time" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-red-400 text-sm" 
                           value="{{ $restaurant->closing_time ?? '' }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Min Order (£) *</label>
                    <input type="number" name="min_order" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-green-400 text-sm" 
                           value="{{ $restaurant->min_order ?? '' }}" min="0" step="0.01" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 text-sm" required>
                        <option value="active" {{ ($restaurant->status ?? 'active') == 'active' ? 'selected' : '' }}>✅ Active</option>
                        <option value="inactive" {{ ($restaurant->status ?? 'active') == 'inactive' ? 'selected' : '' }}>❌ Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Cuisine & Delivery -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Cuisine Tags</label>
                    <input type="text" name="cuisine_tags" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-pink-400 text-sm" 
                           value="{{ $restaurant->cuisine_tags ?? '' }}" placeholder="e.g., Italian, Chinese, Vegan">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Delivery Zone</label>
                    <input type="text" name="delivery_zone" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-orange-400 text-sm" 
                           value="{{ $restaurant->delivery_zone ?? '' }}" placeholder="e.g., 5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Delivery Postcode</label>
                    <input type="text" name="delivery_postcode" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 text-sm" 
                           value="{{ $restaurant->delivery_postcode ?? '' }}" placeholder="e.g., SW1A 1AA">
                </div>
            </div>

            <!-- Media -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Logo</label>
                    <input type="file" name="logo" accept="image/*" class="w-full border px-3 py-2 rounded-md shadow-sm text-sm">
                    @if($restaurant->logo)
                        <img src="{{ asset('storage/'.$restaurant->logo) }}" class="mt-2 h-12 rounded shadow">
                    @endif
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Banner</label>
                    <input type="file" name="banner" accept="image/*" class="w-full border px-3 py-2 rounded-md shadow-sm text-sm">
                    @if($restaurant->banner)
                        <img src="{{ asset('storage/'.$restaurant->banner) }}" class="mt-2 h-12 rounded shadow">
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('restaurants.index') }}" class="px-4 py-2 border rounded mr-2">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
        </div>
    </form>
</div>
@endsection