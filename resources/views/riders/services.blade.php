@extends('layouts.app')

@section('title', $rider->name . ' - Services')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-cogs text-green-600"></i>
                    {{ $rider->name }} - Services
                </h1>
                <p class="text-gray-600 mt-2">Comprehensive delivery services and rider information</p>
            </div>
            
            <!-- Back Button -->
            <a href="{{ route('riders.show', $rider) }}" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2 shadow" style="background-color: #00d03c;">
                <i class="fas fa-arrow-left"></i> Back to Rider
            </a>
        </div>

        <!-- Rider Info -->
        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            @if($rider->photo)
                <img src="{{ asset('storage/'.$rider->photo) }}" 
                     alt="Rider Photo" 
                     class="h-16 w-16 object-cover rounded-full shadow-lg border-2 border-white">
            @else
                <div class="h-16 w-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-lg border-2 border-white">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-800">{{ $rider->name }}</h3>
                @if($rider->vehicle_type)
                    <p class="text-sm text-gray-600">{{ $rider->vehicle_type }} Rider</p>
                @endif
                @if($rider->city)
                    <p class="text-sm text-gray-600">{{ $rider->city }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Service Statistics Boxes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Deliveries -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Deliveries</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $serviceStats['total_deliveries'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">
                        <i class="fas fa-shipping-fast"></i> All time deliveries
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                    <i class="fas fa-box text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Hours -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active Hours</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $serviceStats['active_hours'] }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-clock"></i> This week
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Earnings -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Earnings</p>
                    <p class="text-3xl font-bold text-gray-800">£{{ $serviceStats['earnings'] }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-pound-sign"></i> This month
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl">
                    <i class="fas fa-pound-sign text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Rating -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Customer Rating</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $serviceStats['rating'] }}</p>
                    <p class="text-xs text-yellow-600 mt-1">
                        <i class="fas fa-star"></i> Out of 5.0
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl">
                    <i class="fas fa-star text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Services -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-truck text-blue-600"></i>
            Delivery Services Offered
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($deliveryServices as $service => $features)
            <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-3">
                    @if($service === 'Food Delivery')
                        <i class="fas fa-utensils text-red-500 text-xl"></i>
                    @elseif($service === 'Grocery Delivery')
                        <i class="fas fa-shopping-basket text-green-500 text-xl"></i>
                    @elseif($service === 'Package Delivery')
                        <i class="fas fa-box text-blue-500 text-xl"></i>
                    @elseif($service === 'Express Delivery')
                        <i class="fas fa-rocket text-purple-500 text-xl"></i>
                    @endif
                    <h4 class="font-semibold text-gray-800">{{ $service }}</h4>
                </div>
                <ul class="space-y-1 text-sm text-gray-600">
                    @foreach($features as $feature)
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500 text-xs"></i>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pricing Information -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-tags text-green-600"></i>
            Pricing & Rates
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                <i class="fas fa-pound-sign text-blue-600 text-2xl mb-2"></i>
                <h4 class="font-semibold text-gray-800">Base Fee</h4>
                <p class="text-2xl font-bold text-blue-600">£{{ $pricing['base_fee'] }}</p>
                <p class="text-xs text-gray-600">Per delivery</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                <i class="fas fa-route text-green-600 text-2xl mb-2"></i>
                <h4 class="font-semibold text-gray-800">Per KM</h4>
                <p class="text-2xl font-bold text-green-600">£{{ $pricing['per_km'] }}</p>
                <p class="text-xs text-gray-600">Distance charge</p>
            </div>
            <div class="text-center p-4 bg-orange-50 rounded-lg border border-orange-200">
                <i class="fas fa-clock text-orange-600 text-2xl mb-2"></i>
                <h4 class="font-semibold text-gray-800">Rush Hour</h4>
                <p class="text-2xl font-bold text-orange-600">£{{ $pricing['rush_hour'] }}</p>
                <p class="text-xs text-gray-600">Peak time extra</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                <i class="fas fa-moon text-purple-600 text-2xl mb-2"></i>
                <h4 class="font-semibold text-gray-800">Late Night</h4>
                <p class="text-2xl font-bold text-purple-600">£{{ $pricing['late_night'] }}</p>
                <p class="text-xs text-gray-600">After 10 PM</p>
            </div>
        </div>
    </div>

    <!-- Service Coverage Areas -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-map-marked-alt text-red-600"></i>
            Service Coverage Areas
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($serviceAreas as $area => $subAreas)
            <div class="p-4 border border-gray-200 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-red-500"></i>
                    {{ $area }}
                </h4>
                <div class="space-y-2">
                    @foreach($subAreas as $subArea)
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                            {{ $subArea }}
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Vehicle Information -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-car text-indigo-600"></i>
            Vehicle & Documentation
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-800 mb-3">Vehicle Details</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Vehicle Type</span>
                        <span class="text-gray-800 font-medium">{{ $rider->vehicle_type ?? 'Not specified' }}</span>
                    </div>
                    @if($rider->vehicle_number)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Vehicle Number</span>
                        <span class="text-gray-800 font-medium">{{ $rider->vehicle_number }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Insurance Status</span>
                        <span class="text-green-600 font-medium">
                            @if($rider->insurance_doc)
                                <i class="fas fa-check-circle"></i> Insured
                            @else
                                <i class="fas fa-times-circle"></i> Not Insured
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">MOT Status</span>
                        <span class="text-green-600 font-medium">
                            @if($rider->mot_doc)
                                <i class="fas fa-check-circle"></i> Valid MOT
                            @else
                                <i class="fas fa-times-circle"></i> MOT Required
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-3">Documentation</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Right to Work</span>
                        <span class="text-green-600 font-medium">
                            @if($rider->right_to_work_doc)
                                <i class="fas fa-check-circle"></i> Verified
                            @else
                                <i class="fas fa-times-circle"></i> Not Verified
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Profile Photo</span>
                        <span class="text-green-600 font-medium">
                            @if($rider->photo)
                                <i class="fas fa-check-circle"></i> Uploaded
                            @else
                                <i class="fas fa-times-circle"></i> Not Uploaded
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Availability & Contact -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-calendar-alt text-purple-600"></i>
            Availability & Contact
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-800 mb-3">Working Schedule</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Monday - Friday</span>
                        <span class="text-gray-800 font-medium">8:00 AM - 8:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Saturday</span>
                        <span class="text-gray-800 font-medium">9:00 AM - 6:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sunday</span>
                        <span class="text-gray-800 font-medium">10:00 AM - 4:00 PM</span>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-3">Contact Information</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-phone text-green-500"></i>
                        <span class="text-gray-800">{{ $rider->phone }}</span>
                    </div>
                    @if($rider->email)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-envelope text-blue-500"></i>
                        <span class="text-gray-800">{{ $rider->email }}</span>
                    </div>
                    @endif
                    @if($rider->city)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-red-500"></i>
                        <span class="text-gray-800">{{ $rider->city }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <button class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <i class="fas fa-phone"></i> Book This Rider
            </button>
            <button class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fas fa-comment"></i> Send Message
            </button>
            <button class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center gap-2">
                <i class="fas fa-map-marker-alt"></i> Track Location
            </button>
        </div>
    </div>
</div>
@endsection
