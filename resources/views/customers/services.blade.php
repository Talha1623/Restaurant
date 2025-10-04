@extends('layouts.app')

@section('title', $customer->first_name . ' ' . $customer->last_name . ' - Services')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-cogs text-green-600"></i>
                    {{ $customer->first_name }} {{ $customer->last_name }} - Services
                </h1>
                <p class="text-gray-600 mt-2">Comprehensive customer information and service details</p>
            </div>
            
            <!-- Back Button -->
            <a href="{{ route('customers.show', $customer) }}" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2 shadow" style="background-color: #00d03c;">
                <i class="fas fa-arrow-left"></i> Back to Customer
            </a>
        </div>

        <!-- Customer Info -->
        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
            <div class="h-16 w-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-lg border-2 border-white">
                <i class="fas fa-user text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">{{ $customer->first_name }} {{ $customer->last_name }}</h3>
                <p class="text-sm text-gray-600">{{ $customer->email }}</p>
                @if($customer->city)
                    <p class="text-sm text-gray-600">{{ $customer->city }}, {{ $customer->country ?? 'UK' }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Customer Statistics Boxes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Orders -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $customerStats['total_orders'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">
                        <i class="fas fa-shopping-bag"></i> All time orders
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                    <i class="fas fa-shopping-cart text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Spent</p>
                    <p class="text-3xl font-bold text-gray-800">£{{ number_format($customerStats['total_spent'], 2) }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-pound-sign"></i> Lifetime spending
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                    <i class="fas fa-pound-sign text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Favorite Cuisine -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Favorite Cuisine</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $customerStats['favorite_cuisine'] }}</p>
                    <p class="text-xs text-orange-600 mt-1">
                        <i class="fas fa-utensils"></i> Most ordered
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl">
                    <i class="fas fa-utensils text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Loyalty Points -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Loyalty Points</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $customerStats['loyalty_points'] }}</p>
                    <p class="text-xs text-purple-600 mt-1">
                        <i class="fas fa-star"></i> Available points
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl">
                    <i class="fas fa-star text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-history text-blue-600"></i>
            Recent Order History
        </h3>
        <div class="space-y-4">
            @foreach($orderHistory as $order)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr($order['id'], -3) }}
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">{{ $order['restaurant'] }}</h4>
                        <p class="text-sm text-gray-600">{{ $order['items'] }}</p>
                        <p class="text-xs text-gray-500">{{ $order['date'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-gray-800">£{{ number_format($order['amount'], 2) }}</p>
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($order['status'] === 'Delivered') bg-green-100 text-green-700
                        @elseif($order['status'] === 'In Progress') bg-orange-100 text-orange-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ $order['status'] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Favorite Restaurants -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-heart text-red-600"></i>
            Favorite Restaurants
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($favoriteRestaurants as $restaurant => $details)
            <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                <div class="text-center mb-3">
                    <i class="fas fa-store text-red-500 text-2xl mb-2"></i>
                    <h4 class="font-semibold text-gray-800">{{ $restaurant }}</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Orders:</span>
                        <span class="font-medium">{{ $details['orders'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rating:</span>
                        <span class="text-yellow-500 font-medium">{{ $details['rating'] }}/5</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Order:</span>
                        <span class="text-xs text-gray-500">{{ $details['last_order'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Delivery Addresses -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-map-marker-alt text-green-600"></i>
            Delivery Addresses
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($deliveryAddresses as $type => $address)
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold text-gray-800">{{ $type }}</h4>
                    @if($address['default'])
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Default</span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ $address['address'] }}</p>
                @if($address['instructions'])
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-info-circle"></i> {{ $address['instructions'] }}
                    </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-credit-card text-indigo-600"></i>
            Payment Methods
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($paymentMethods as $method => $details)
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    @if(str_contains($method, 'Visa'))
                        <i class="fab fa-cc-visa text-blue-500 text-2xl"></i>
                    @elseif(str_contains($method, 'PayPal'))
                        <i class="fab fa-paypal text-blue-500 text-2xl"></i>
                    @else
                        <i class="fas fa-money-bill-wave text-green-500 text-2xl"></i>
                    @endif
                    <h4 class="font-semibold text-gray-800">{{ $method }}</h4>
                </div>
                <div class="space-y-1 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Type:</span>
                        <span>{{ $details['type'] }}</span>
                    </div>
                    @if(isset($details['expiry']))
                        <div class="flex justify-between">
                            <span>Expiry:</span>
                            <span>{{ $details['expiry'] }}</span>
                        </div>
                    @endif
                    @if(isset($details['email']))
                        <div class="flex justify-between">
                            <span>Email:</span>
                            <span>{{ $details['email'] }}</span>
                        </div>
                    @endif
                    @if(isset($details['preference']))
                        <div class="flex justify-between">
                            <span>Preference:</span>
                            <span>{{ $details['preference'] }}</span>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Loyalty & Rewards -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-gift text-yellow-600"></i>
            Loyalty & Rewards
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-800 mb-3">Current Status</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Current Points</span>
                        <span class="text-2xl font-bold text-purple-600">{{ $loyaltyRewards['current_points'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Next Reward</span>
                        <span class="text-green-600 font-medium">{{ $loyaltyRewards['next_reward'] }}</span>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-3">Available Rewards</h4>
                <div class="space-y-2">
                    @foreach($loyaltyRewards['available_rewards'] as $reward)
                    <div class="flex items-center gap-2 p-2 bg-green-50 rounded-lg border border-green-200">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-sm text-gray-700">{{ $reward }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Communication Preferences -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-comments text-blue-600"></i>
            Communication Preferences
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-800 mb-3">Notification Settings</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Email Notifications</span>
                        <span class="text-green-600 font-medium">
                            <i class="fas fa-check-circle"></i> Enabled
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">SMS Alerts</span>
                        <span class="text-green-600 font-medium">
                            <i class="fas fa-check-circle"></i> Enabled
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Push Notifications</span>
                        <span class="text-orange-600 font-medium">
                            <i class="fas fa-times-circle"></i> Disabled
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-3">Marketing Preferences</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Newsletter</span>
                        <span class="text-green-600 font-medium">
                            <i class="fas fa-check-circle"></i> Subscribed
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Special Offers</span>
                        <span class="text-green-600 font-medium">
                            <i class="fas fa-check-circle"></i> Enabled
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">Partner Promotions</span>
                        <span class="text-orange-600 font-medium">
                            <i class="fas fa-times-circle"></i> Disabled
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <button class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <i class="fas fa-phone"></i> Contact Customer
            </button>
            <button class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fas fa-envelope"></i> Send Email
            </button>
            <button class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center gap-2">
                <i class="fas fa-gift"></i> Send Offer
            </button>
            <button class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition flex items-center gap-2">
                <i class="fas fa-chart-line"></i> View Analytics
            </button>
        </div>
    </div>
</div>
@endsection
