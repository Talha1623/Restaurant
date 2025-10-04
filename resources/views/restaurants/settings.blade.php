@extends('layouts.app')

@section('title', $restaurant->name . ' - Settings')
@section('content')
<div class="max-w-7xl mx-auto space-y-4 p-3">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <a href="{{ route('restaurants.show', $restaurant) }}" class="hover:text-green-600 transition-colors">
                        <i class="fas fa-arrow-left" style="color: #00d03c;"></i>
                    </a>
                    {{ $restaurant->name }} - Settings
                </h3>
                <p class="text-gray-600 text-sm mt-1">Manage your restaurant configuration and preferences</p>
            </div>
            
            <!-- Save Button -->
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button class="tab-button active py-4 px-1 border-b-2 border-green-500 font-medium text-sm text-green-600" data-tab="general">
                    <i class="fas fa-cog mr-2"></i>General
                </button>
                <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="menu">
                    <i class="fas fa-utensils mr-2"></i>Menu
                </button>
                <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="delivery">
                    <i class="fas fa-truck mr-2"></i>Delivery
                </button>
                <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="payment">
                    <i class="fas fa-credit-card mr-2"></i>Payment
                </button>
                <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="notifications">
                    <i class="fas fa-bell mr-2"></i>Notifications
                </button>
                <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="staff">
                    <i class="fas fa-users mr-2"></i>Staff
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- General Settings Tab -->
            <div id="general-tab" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Restaurant Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-800">Restaurant Information</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Restaurant Name</label>
                            <input type="text" value="{{ $restaurant->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Brief description of your restaurant...">Traditional British cuisine with a modern twist. Fresh ingredients, authentic recipes, and warm hospitality.</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ $restaurant->email }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" value="{{ $restaurant->phone }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Full address...">{{ $restaurant->address }}</textarea>
                        </div>
                    </div>

                    <!-- Operating Hours -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-800">Operating Hours</h4>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Monday</span>
                                <div class="flex items-center gap-2">
                                    <input type="time" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    <span class="text-gray-500">to</span>
                                    <input type="time" value="22:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Tuesday</span>
                                <div class="flex items-center gap-2">
                                    <input type="time" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    <span class="text-gray-500">to</span>
                                    <input type="time" value="22:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Wednesday</span>
                                <div class="flex items-center gap-2">
                                    <input type="time" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    <span class="text-gray-500">to</span>
                                    <input type="time" value="22:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Thursday</span>
                                <div class="flex items-center gap-2">
                                    <input type="time" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    <span class="text-gray-500">to</span>
                                    <input type="time" value="22:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Friday</span>
                                <div class="flex items-center gap-2">
                                    <input type="time" value="09:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    <span class="text-gray-500">to</span>
                                    <input type="time" value="23:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Saturday</span>
                                <div class="flex items-center gap-2">
                                    <input type="time" value="10:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    <span class="text-gray-500">to</span>
                                    <input type="time" value="23:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Sunday</span>
                                <div class="flex items-center gap-2">
                                    <input type="time" value="10:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                    <span class="text-gray-500">to</span>
                                    <input type="time" value="21:00" class="px-2 py-1 border border-gray-300 rounded text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Settings Tab -->
            <div id="menu-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <h4 class="text-lg font-semibold text-gray-800">Menu Management</h4>
                    
                    <!-- Menu Categories -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="font-medium text-gray-700 mb-3">Menu Categories</h5>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between bg-white p-3 rounded border">
                                <span class="text-sm">Starters</span>
                                <div class="flex gap-2">
                                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</button>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Delete</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between bg-white p-3 rounded border">
                                <span class="text-sm">Main Course</span>
                                <div class="flex gap-2">
                                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</button>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Delete</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between bg-white p-3 rounded border">
                                <span class="text-sm">Desserts</span>
                                <div class="flex gap-2">
                                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</button>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Delete</button>
                                </div>
                            </div>
                        </div>
                        <button class="mt-3 px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-1"></i> Add Category
                        </button>
                    </div>

                    <!-- Menu Items -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="font-medium text-gray-700 mb-3">Menu Items</h5>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between bg-white p-3 rounded border">
                                <div>
                                    <span class="text-sm font-medium">Fish & Chips</span>
                                    <span class="text-xs text-gray-500 ml-2">PKR 12.99</span>
                                </div>
                                <div class="flex gap-2">
                                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</button>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Delete</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between bg-white p-3 rounded border">
                                <div>
                                    <span class="text-sm font-medium">Chicken Tikka Masala</span>
                                    <span class="text-xs text-gray-500 ml-2">PKR 14.99</span>
                                </div>
                                <div class="flex gap-2">
                                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</button>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Delete</button>
                                </div>
                            </div>
                        </div>
                        <button class="mt-3 px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </div>
                </div>
            </div>

            <!-- Delivery Settings Tab -->
            <div id="delivery-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-800">Delivery Zones</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Radius (km)</label>
                            <input type="number" value="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Order Amount</label>
                            <input type="number" value="25" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Fee</label>
                            <input type="number" value="3.99" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Delivery Time (minutes)</label>
                            <input type="number" value="30" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-800">Service Areas</h4>
                        
                        <div class="space-y-2">
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                <span class="text-sm">Central London</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500">Free delivery</span>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Remove</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                <span class="text-sm">East London</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500">PKR 2.99</span>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Remove</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                <span class="text-sm">West London</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500">PKR 3.99</span>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Remove</button>
                                </div>
                            </div>
                        </div>
                        
                        <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-1"></i> Add Service Area
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payment Settings Tab -->
            <div id="payment-tab" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-800">Payment Methods</h4>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-credit-card text-blue-600"></i>
                                    <span class="text-sm font-medium">Credit/Debit Card</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" checked class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-mobile-alt text-green-600"></i>
                                    <span class="text-sm font-medium">Mobile Payment</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" checked class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-money-bill-wave text-yellow-600"></i>
                                    <span class="text-sm font-medium">Cash on Delivery</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-800">Tax Settings</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">VAT Rate (%)</label>
                            <input type="number" value="20" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service Charge (%)</label>
                            <input type="number" value="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Platform Commission (%)</label>
                            <input type="number" value="15" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div id="notifications-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <h4 class="text-lg font-semibold text-gray-800">Notification Settings</h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h5 class="font-medium text-gray-700">Order Notifications</h5>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">New Order Alerts</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">Order Status Updates</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">Cancellation Alerts</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <h5 class="font-medium text-gray-700">Customer Notifications</h5>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">Promotional Emails</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">Order Confirmations</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">Delivery Updates</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Management Tab -->
            <div id="staff-tab" class="tab-content hidden">
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-gray-800">Staff Management</h4>
                        <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-1"></i> Add Staff
                        </button>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between bg-white p-3 rounded border">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium">John Smith</span>
                                        <span class="text-xs text-gray-500 block">john@restaurant.com</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Admin</span>
                                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</button>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Remove</button>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between bg-white p-3 rounded border">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium">Sarah Johnson</span>
                                        <span class="text-xs text-gray-500 block">sarah@restaurant.com</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Manager</span>
                                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</button>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Remove</button>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between bg-white p-3 rounded border">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-orange-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium">Mike Wilson</span>
                                        <span class="text-xs text-gray-500 block">mike@restaurant.com</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded">Staff</span>
                                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</button>
                                    <button class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-green-500', 'text-green-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active class to clicked button
            button.classList.add('active', 'border-green-500', 'text-green-600');
            button.classList.remove('border-transparent', 'text-gray-500');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show target tab content
            document.getElementById(targetTab + '-tab').classList.remove('hidden');
        });
    });
});
</script>
@endsection
