@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 py-3">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-3">
            <div>
                <h1 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <a href="{{ route('restaurants.show', $restaurantId) }}" class="hover:text-green-600 transition-colors">
                        <i class="fas fa-arrow-left" style="color: #00d03c;"></i>
                    </a>
                    Menu
                </h1>
                @if($restaurantId)
                    @php
                        $restaurant = \App\Models\Restaurant::find($restaurantId);
                    @endphp
                    @if($restaurant)
                        <p class="text-gray-600 mt-1 text-sm">{{ $restaurant->legal_name }} - {{ $restaurant->business_name }}</p>
                    @endif
                @endif
            </div>
            <a href="{{ route('menus.create', ['restaurant_id' => $restaurantId]) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 text-sm rounded-lg transition">
                <i class="fas fa-plus mr-1"></i>Add Item
            </a>
        </div>


        <!-- Success Message -->
        @if(session('success'))
            <div id="success-message" class="mb-3 p-2 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2 text-sm"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                    <button onclick="hideSuccessMessage()" class="text-green-600 hover:text-green-800">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Addons Management Section -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200 mb-3">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-glass-whiskey text-purple-600"></i>
                    Restaurant Addons
                </h3>
                <div class="flex items-center gap-2">
                    <button onclick="toggleAllAddonsDropdown()" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 text-xs rounded-lg transition flex items-center gap-1">
                        <i class="fas fa-glass-whiskey mr-1"></i>All Addons
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="allAddonsIcon"></i>
                    </button>
                    <button onclick="openAddAddonModal()" 
                            class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1.5 text-xs rounded-lg transition">
                        <i class="fas fa-plus mr-1"></i>Add Addon
                    </button>
                </div>
            </div>
            
            <!-- Addons List (Hidden by default) -->
            <div id="addons-container" class="hidden">
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-700">All Addons</h4>
                        <div class="flex items-center gap-2">
                            <input type="text" id="addonSearch" placeholder="Search addons..." 
                                   class="px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <button onclick="toggleAllAddonsDropdown()" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div id="addonsList" class="space-y-2 max-h-60 overflow-y-auto">
                        @forelse($restaurantAddons as $addon)
                            <div class="addon-item flex items-center justify-between p-2 bg-white rounded border hover:bg-gray-50">
                                <div class="flex items-center gap-2">
                                    @if($addon->image)
                                        <img src="{{ asset('storage/' . $addon->image) }}" alt="{{ $addon->name }}" class="w-6 h-6 rounded-full object-cover">
                                    @else
                                        <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center">
                                            <i class="fas fa-glass-whiskey text-gray-400 text-xs"></i>
                                        </div>
                                    @endif
                                    <span class="text-sm text-gray-700">{{ $addon->name }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $addon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $addon->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button onclick="openEditAddonModal({{ $addon->id }}, '{{ $addon->name }}', '{{ $addon->image }}')" 
                                            class="p-1 text-blue-600 hover:bg-blue-100 rounded">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <form method="POST" action="{{ route('restaurants.addons.toggle', [$restaurantId, $addon->id]) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1 text-yellow-600 hover:bg-yellow-100 rounded">
                                            <i class="fas fa-toggle-{{ $addon->is_active ? 'on' : 'off' }} text-xs"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('restaurants.addons.destroy', [$restaurantId, $addon->id]) }}" 
                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this addon?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-600 hover:bg-red-100 rounded">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-glass-whiskey text-2xl text-gray-300 mb-2"></i>
                                <p class="text-sm">No addons found</p>
                                <p class="text-xs text-gray-400">Add your first addon using the button above</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-3">
            <div class="bg-white p-2 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-1.5 bg-blue-100 rounded-lg">
                        <i class="fas fa-utensils text-blue-600 text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Total Items</p>
                        <p class="text-lg font-bold text-gray-900">{{ $menus->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-2 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-1.5 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Active Items</p>
                        <p class="text-lg font-bold text-gray-900">{{ $menus->where('status', 'active')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-2 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-1.5 bg-yellow-100 rounded-lg">
                        <i class="fas fa-pause-circle text-yellow-600 text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Inactive Items</p>
                        <p class="text-lg font-bold text-gray-900">{{ $menus->where('status', 'inactive')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-2 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="p-1.5 bg-purple-100 rounded-lg">
                        <i class="fas fa-tags text-purple-600 text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Categories</p>
                        <p class="text-lg font-bold text-gray-900">{{ $menus->pluck('category')->unique()->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VAT Price</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Availability</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spice Level</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prep Time</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($menus as $menu)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-xs font-medium text-gray-900">{{ $menu->name }}</div>
                                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($menu->description, 30) }}</div>
                                            @if($menu->tags && count($menu->tags) > 0)
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @foreach(array_slice($menu->tags, 0, 2) as $tag)
                                                        <span class="inline-flex items-center px-1 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                            {{ $tag }}
                                                        </span>
                                                    @endforeach
                                                    @if(count($menu->tags) > 2)
                                                        <span class="text-xs text-gray-400">+{{ count($menu->tags) - 2 }} more</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $menu->category }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-900">
                                    <div class="flex items-center">
                                        @switch($menu->currency)
                                            @case('GBP')
                                                £{{ number_format($menu->price, 2) }}
                                                @break
                                            @case('USD')
                                                ${{ number_format($menu->price, 2) }}
                                                @break
                                            @case('EUR')
                                                €{{ number_format($menu->price, 2) }}
                                                @break
                                            @case('PKR')
                                                ₨{{ number_format($menu->price, 2) }}
                                                @break
                                            @default
                                                £{{ number_format($menu->price, 2) }}
                                        @endswitch
                                    </div>
                                    @if($menu->calories)
                                        <div class="text-xs text-gray-500">{{ $menu->calories }} cal</div>
                                    @endif
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-900">
                                    @if($menu->vat_price)
                                        <div class="flex items-center">
                                            @switch($menu->currency)
                                                @case('GBP')
                                                    £{{ number_format($menu->vat_price, 2) }}
                                                    @break
                                                @case('USD')
                                                    ${{ number_format($menu->vat_price, 2) }}
                                                    @break
                                                @case('EUR')
                                                    €{{ number_format($menu->vat_price, 2) }}
                                                    @break
                                                @case('PKR')
                                                    ₨{{ number_format($menu->vat_price, 2) }}
                                                    @break
                                                @default
                                                    £{{ number_format($menu->vat_price, 2) }}
                                            @endswitch
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $menu->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas {{ $menu->is_available ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $menu->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">
                                    @if($menu->spice_level > 0)
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-xs {{ $i <= $menu->spice_level ? 'text-red-500' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">No Spice</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-900">
                                    @if($menu->preparation_time)
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-gray-400 mr-1"></i>
                                            {{ $menu->preparation_time }}m
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $menu->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($menu->status) }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap">
                                    @if($menu->images && $menu->images->count() > 0)
                                        <div class="flex -space-x-1">
                                            @foreach($menu->images->take(3) as $index => $image)
                                                <img src="{{ asset('storage/' . $image->image_url) }}" 
                                                     alt="{{ $menu->name }} - Image {{ $index + 1 }}" 
                                                     class="h-8 w-8 rounded-lg object-cover border-2 border-white shadow-sm">
                                            @endforeach
                                            @if($menu->images->count() > 3)
                                                <div class="h-8 w-8 bg-gray-200 rounded-lg flex items-center justify-center border-2 border-white shadow-sm">
                                                    <span class="text-xs font-medium text-gray-600">+{{ $menu->images->count() - 3 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}" 
                                             alt="{{ $menu->name }}" 
                                             class="h-8 w-8 rounded-lg object-cover">
                                    @else
                                        <div class="h-8 w-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-xs"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-2 py-2 whitespace-nowrap text-xs font-medium">
                                    <div class="flex items-center space-x-1">
                                        <a href="{{ route('menus.show', $menu) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('menus.edit', $menu) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('menus.destroy', $menu) }}" method="POST" 
                                              class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this menu item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-2 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-utensils text-2xl text-gray-300 mb-1"></i>
                                        <p class="text-sm font-medium">No menu items found</p>
                                        <p class="text-xs">Start by adding your first menu item</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($menus->hasPages())
                <div class="bg-white px-2 py-2 border-t border-gray-200 sm:px-4">
                    {{ $menus->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Addon Modal -->
<div id="addAddonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-4 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-bold text-gray-900">Add New Addon</h3>
            <button onclick="closeAddAddonModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="addAddonForm" method="POST" action="{{ route('restaurants.addons.store', $restaurantId) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="redirect_to" value="menu">
            
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Addon Name *</label>
                <input type="text" name="name" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Image (Optional)</label>
                <input type="file" name="image" accept="image/*" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                <p class="text-xs text-gray-500 mt-1">Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddAddonModal()" 
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm text-white bg-orange-600 rounded-md hover:bg-orange-700">
                    Add Addon
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Addon Modal -->
<div id="editAddonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-4 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-bold text-gray-900">Edit Addon</h3>
            <button onclick="closeEditAddonModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editAddonForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="addon_id" id="editAddonId">
            <input type="hidden" name="redirect_to" value="menu">
            
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Addon Name *</label>
                <input type="text" name="name" id="editAddonName" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            
            <div class="mb-3" id="currentImageContainer" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
                <img id="currentAddonImage" src="" alt="Current Image" class="w-16 h-16 rounded-lg object-cover">
                <p class="text-xs text-gray-500 mt-1">Upload a new image to replace it</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">New Image (Optional)</label>
                <input type="file" name="image" accept="image/*" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                <p class="text-xs text-gray-500 mt-1">Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditAddonModal()" 
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm text-white bg-orange-600 rounded-md hover:bg-orange-700">
                    Update Addon
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Ingredient Modal -->
<div id="addIngredientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-4 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-bold text-gray-900">Add New Ingredient</h3>
            <button onclick="closeAddIngredientModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="addIngredientForm">
            @csrf
            
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient Name *</label>
                <input type="text" name="name" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Allergen Information (Optional)</label>
                <input type="text" name="allergen_info" 
                       placeholder="e.g., Contains Nuts, Dairy Free, Gluten Free"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddIngredientModal()" 
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm text-white bg-purple-600 rounded-md hover:bg-purple-700">
                    Add Ingredient
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Ingredient Modal -->
<div id="editIngredientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-4 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-bold text-gray-900">Edit Ingredient</h3>
            <button onclick="closeEditIngredientModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editIngredientForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="ingredient_id" id="edit_ingredient_id">
            
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient Name *</label>
                <input type="text" name="name" id="edit_ingredient_name" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Allergen Information (Optional)</label>
                <input type="text" name="allergen_info" id="edit_ingredient_allergen" 
                       placeholder="e.g., Contains Nuts, Dairy Free, Gluten Free"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditIngredientModal()" 
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm text-white bg-purple-600 rounded-md hover:bg-purple-700">
                    Update Ingredient
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Restaurant Action Buttons -->
@if($restaurantId)
    @php
        $restaurant = \App\Models\Restaurant::find($restaurantId);
    @endphp
    @if($restaurant)
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200 mt-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
                <a href="{{ route('menus.index', ['restaurant_id' => $restaurant->id]) }}" 
                   class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-green-600 hover:bg-green-700">
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
    @endif
@endif

<script>
    // Auto-hide success message after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(function() {
                hideSuccessMessage();
            }, 5000); // 5 seconds
        }
    });

    // Hide success message function
    function hideSuccessMessage() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.transition = 'opacity 0.5s ease';
            successMessage.style.opacity = '0';
            setTimeout(function() {
                successMessage.remove();
            }, 500);
        }
    }


    // Show Notification
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-400' : 'bg-red-100 text-red-800 border border-red-400'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                ${message}
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }


    // Addon Functions
    function toggleAllAddonsDropdown() {
        const container = document.getElementById('addons-container');
        const icon = document.getElementById('allAddonsIcon');
        
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            container.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    function openAddAddonModal() {
        document.getElementById('addAddonModal').classList.remove('hidden');
        document.getElementById('addAddonModal').classList.add('flex');
    }

    function closeAddAddonModal() {
        document.getElementById('addAddonModal').classList.add('hidden');
        document.getElementById('addAddonModal').classList.remove('flex');
        document.getElementById('addAddonForm').reset();
    }

    function openEditAddonModal(id, name, image) {
        document.getElementById('editAddonId').value = id;
        document.getElementById('editAddonName').value = name;
        
        // Set the form action URL
        const form = document.getElementById('editAddonForm');
        form.action = '{{ route("restaurants.addons.update", [$restaurantId, ":id"]) }}'.replace(':id', id);
        
        const currentImageContainer = document.getElementById('currentImageContainer');
        const currentImage = document.getElementById('currentAddonImage');
        
        if (image) {
            currentImage.src = '/storage/' + image;
            currentImageContainer.style.display = 'block';
        } else {
            currentImageContainer.style.display = 'none';
        }
        
        document.getElementById('editAddonModal').classList.remove('hidden');
        document.getElementById('editAddonModal').classList.add('flex');
    }

    function closeEditAddonModal() {
        document.getElementById('editAddonModal').classList.add('hidden');
        document.getElementById('editAddonModal').classList.remove('flex');
        document.getElementById('editAddonForm').reset();
    }

    // Add Addon Form Submit
    document.getElementById('addAddonForm').addEventListener('submit', function(e) {
        // Let the form submit normally for now to avoid AJAX issues
        // The form will redirect back to the same page with success message
        return true;
    });

    // Edit Addon Form Submit
    document.getElementById('editAddonForm').addEventListener('submit', function(e) {
        // Let the form submit normally for now to avoid AJAX issues
        // The form will redirect back to the same page with success message
        return true;
    });

    // Addon Search Function
    function setupAddonSearch() {
        const searchInput = document.getElementById('addonSearch');
        const addonItems = document.querySelectorAll('.addon-item');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            addonItems.forEach(item => {
                const addonName = item.querySelector('span').textContent.toLowerCase();
                if (addonName.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Initialize addon search on page load
    document.addEventListener('DOMContentLoaded', function() {
        setupAddonSearch();
    });
</script>
@endsection
