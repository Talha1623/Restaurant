@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg border border-gray-200">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4 border-b pb-2">
        <h2 class="text-xl font-bold flex items-center gap-2 text-gray-800">
            <a href="{{ route('restaurants.index') }}" class="hover:text-green-600 transition-colors">
                <i class="fas fa-arrow-left text-green-600 hover:text-green-700"></i>
            </a> Add New Restaurant
        </h2>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <h4 class="font-bold">Please fix the following errors:</h4>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('restaurants.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div class="space-y-4">
            <!-- Business & Legal Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Legal Name *</label>
                    <input type="text" name="legal_name" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-green-400 text-sm" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Business Name *</label>
                    <input type="text" name="business_name" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 text-sm" required>
                </div>
            </div>

            <!-- Address Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Address Line 1 *</label>
                    <input type="text" name="address_line1" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-400 text-sm" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">City *</label>
                    <input type="text" name="city" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-pink-400 text-sm" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Postcode *</label>
                    <input type="text" name="postcode" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-orange-400 text-sm" required>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Phone *</label>
                    <input type="tel" name="phone" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-purple-400 text-sm" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Contact Person *</label>
                    <input type="text" name="contact_person" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 text-sm" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-400 text-sm" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Password *</label>
                    <input type="password" name="password" value="restaurant123" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-purple-400 text-sm" required>
                    <p class="text-xs text-gray-500 mt-1">Default password: restaurant123 (can be changed later)</p>
                </div>
            </div>

            <!-- Hours & Order Settings -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Opening Hours *</label>
                    <input type="time" name="opening_time" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-green-400 text-sm" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Closing Hours *</label>
                    <input type="time" name="closing_time" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-red-400 text-sm" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Min Order (£) *</label>
                    <input type="number" name="min_order" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-green-400 text-sm" min="0" step="0.01" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 text-sm" required>
                        <option value="active">✅ Active</option>
                        <option value="inactive">❌ Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Cuisine & Delivery -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Cuisine Tags</label>
                    <input type="text" name="cuisine_tags" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-pink-400 text-sm" placeholder="e.g., Italian, Chinese, Vegan">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Delivery Zone</label>
                    <input type="text" name="delivery_zone" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-orange-400 text-sm" placeholder="e.g., 5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Delivery Postcode</label>
                    <input type="text" name="delivery_postcode" class="w-full border px-3 py-2 rounded-md shadow-sm focus:ring-2 focus:ring-blue-400 text-sm" placeholder="e.g., SW1A 1AA">
                </div>
            </div>

            <!-- Media -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Logo</label>
                    <input type="file" name="logo" accept="image/*" class="w-full border px-3 py-2 rounded-md shadow-sm text-sm">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Banner</label>
                    <input type="file" name="banner" accept="image/*" class="w-full border px-3 py-2 rounded-md shadow-sm text-sm">
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end mt-6 gap-3">
            <a href="{{ route('restaurants.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-100 flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 flex items-center gap-1">
                <i class="fas fa-save"></i> Save
            </button>
        </div>
    </form>
</div>

@endsection
