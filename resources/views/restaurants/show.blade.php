@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-3">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <a href="{{ route('restaurants.index') }}" class="hover:text-green-600 transition-colors">
                    <i class="fas fa-arrow-left" style="color: #00d03c;"></i>
                </a>
                Restaurant Profile
            </h3>
            
            <!-- Action Buttons -->
            <div class="flex gap-2">
                <a href="{{ route('restaurants.edit', $restaurant) }}" 
                   class="px-2 py-1 bg-indigo-600 text-white text-xs rounded-md hover:bg-indigo-700 transition flex items-center gap-1">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>

        <!-- Restaurant Profile Information -->
        <div class="flex items-center gap-3">
            <!-- Restaurant Logo -->
            <div class="flex-shrink-0">
                @if($restaurant->logo)
                    <img src="{{ asset('storage/'.$restaurant->logo) }}" 
                         alt="Restaurant Logo" 
                         class="h-12 w-12 object-cover rounded-full shadow border-2 border-white">
                @else
                    <div class="h-12 w-12 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center shadow border-2 border-white">
                        <i class="fas fa-store text-white text-sm"></i>
                    </div>
                @endif
            </div>

            <!-- Basic Info -->
            <div class="flex-1">
                <h2 class="text-lg font-bold text-gray-800 mb-1">Restaurant Profile</h2>
                <div class="flex flex-wrap gap-2 text-xs text-gray-600">
                    @if($restaurant->contact_person)
                        <span class="flex items-center gap-1">
                            <i class="fas fa-user-tie text-blue-500"></i>
                            {{ $restaurant->contact_person }}
                        </span>
                    @endif
                    @if($restaurant->phone)
                        <span class="flex items-center gap-1">
                            <i class="fas fa-phone text-green-500"></i>
                            {{ $restaurant->phone }}
                        </span>
                    @endif
                    @if($restaurant->email)
                        <span class="flex items-center gap-1">
                            <i class="fas fa-envelope text-purple-500"></i>
                            {{ $restaurant->email }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Restaurant Details Grid - Compact Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <!-- Address Information -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <h4 class="text-xs font-semibold text-gray-800 mb-2 flex items-center gap-1">
                <i class="fas fa-map-marker-alt text-red-500"></i>
                Address & Business Info
            </h4>
            <div class="space-y-1 text-xs">
                @if($restaurant->legal_name)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Legal Name:</span>
                        <span class="text-gray-800 font-medium">{{ $restaurant->legal_name }}</span>
                </div>
                @endif
                @if($restaurant->business_name)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Business Name:</span>
                        <span class="text-gray-800 font-medium">{{ $restaurant->business_name }}</span>
                </div>
                @endif
                @if($restaurant->address_line1)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Address:</span>
                        <span class="text-gray-800 font-medium">{{ $restaurant->address_line1 }}</span>
                </div>
                @endif
                @if($restaurant->city)
                    <div class="flex justify-between">
                        <span class="text-gray-600">City:</span>
                        <span class="text-gray-800 font-medium">{{ $restaurant->city }}</span>
                </div>
                @endif
                @if($restaurant->postcode)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Postcode:</span>
                        <span class="text-gray-800 font-medium">{{ $restaurant->postcode }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Business Hours -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <h4 class="text-xs font-semibold text-gray-800 mb-2 flex items-center gap-1">
                <i class="fas fa-clock text-orange-500"></i>
                Hours
            </h4>
            <div class="space-y-1 text-xs">
                @if($restaurant->opening_time)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Opening:</span>
                        <span class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($restaurant->opening_time)->format('g:i A') }}</span>
                    </div>
                @endif
                @if($restaurant->closing_time)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Closing:</span>
                        <span class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($restaurant->closing_time)->format('g:i A') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Cuisine & Delivery -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <h4 class="text-xs font-semibold text-gray-800 mb-2 flex items-center gap-1">
                <i class="fas fa-utensils text-pink-500"></i>
                Cuisine & Delivery
            </h4>
            <div class="space-y-1 text-xs">
                @if($restaurant->cuisine_tags)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cuisine:</span>
                        <span class="text-gray-800 font-medium">{{ $restaurant->cuisine_tags }}</span>
                    </div>
                @endif
                @if($restaurant->delivery_zone)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Zone:</span>
                        <span class="text-gray-800 font-medium">{{ $restaurant->delivery_zone }} km</span>
                    </div>
                @endif
                @if($restaurant->delivery_postcode)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Delivery Postcode:</span>
                        <span class="text-gray-800 font-medium">{{ $restaurant->delivery_postcode }}</span>
                    </div>
                @endif
                @if($restaurant->min_order)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Min Order:</span>
                        <span class="text-gray-800 font-medium">£{{ number_format($restaurant->min_order, 2) }}</span>
                    </div>
                @endif
                @if($restaurant->status)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $restaurant->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($restaurant->status) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Banner Image -->
    @if($restaurant->banner)
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <h4 class="text-xs font-semibold text-gray-800 mb-2 flex items-center gap-1">
                <i class="fas fa-image text-blue-500"></i>
                Restaurant Banner
            </h4>
            <div class="flex justify-center">
                <img src="{{ asset('storage/'.$restaurant->banner) }}" 
                     alt="Restaurant Banner" 
                     class="max-w-full h-24 object-cover rounded-lg shadow">
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
            <a href="{{ route('menus.index', ['restaurant_id' => $restaurant->id]) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-utensils"></i> Menu
            </a>
            <a href="{{ route('certificates.index', ['restaurant_id' => $restaurant->id]) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-certificate"></i> Certificates
            </a>
            <a href="{{ route('restaurants.orders', $restaurant) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
            <a href="{{ route('restaurants.analytics', $restaurant) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-chart-bar"></i> Analytics
            </a>
            <a href="{{ route('restaurants.reviews', $restaurant) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-star"></i> Reviews
            </a>
            <a href="{{ route('restaurants.settings', $restaurant) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </div>
</div>
@endsection