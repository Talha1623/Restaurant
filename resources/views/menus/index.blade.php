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

        <!-- Category Management Section -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200 mb-3">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-tags text-green-600"></i>
                    Restaurant Categories
                </h3>
                <div class="flex items-center gap-2">
                    <button onclick="toggleAllCategoriesDropdown()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 text-xs rounded-lg transition flex items-center gap-1">
                        <i class="fas fa-list mr-1"></i>All Category
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="allCategoryIcon"></i>
                    </button>
                    <button onclick="openAddCategoryModal()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 text-xs rounded-lg transition">
                        <i class="fas fa-plus mr-1"></i>Add Category
                    </button>
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

            <!-- Categories List (Hidden by default) -->
            <div id="categories-container" class="hidden">
                @if($restaurantId)
                    @php
                        $restaurant = \App\Models\Restaurant::find($restaurantId);
                        $categories = $restaurant ? $restaurant->categories()->orderBy('name')->get() : collect();
                        $totalCategories = $categories->count();
                        $showLimit = 6; // Show only first 6 categories by default
                    @endphp
                    
                    @if($totalCategories > 0)
                        <!-- Search Box (shown when viewing all categories) -->
                        <div id="category-search" class="mb-3 hidden">
                            <div class="relative">
                                <input type="text" id="categorySearchInput" 
                                       placeholder="Search categories..." 
                                       class="w-full px-3 py-2 pl-8 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                            </div>
                        </div>

                        <!-- Categories Grid -->
                        <div id="categories-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach($categories as $index => $category)
                                <div class="category-item flex items-center justify-between p-2 bg-gray-50 rounded-lg border transition-all duration-200" 
                                     data-category-name="{{ strtolower($category->name) }}"
                                     data-category-id="{{ $category->id }}">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" 
                                                     alt="{{ $category->name }}" 
                                                     class="w-8 h-8 object-cover rounded-full">
                                            @else
                                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-tag text-gray-400 text-xs"></i>
                                                </div>
                                            @endif
                                            <span class="text-xs font-medium text-gray-900">{{ $category->name }}</span>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        @if($category->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($category->description, 30) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button onclick="openEditCategoryModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')" 
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button onclick="toggleCategory({{ $category->id }})" 
                                                class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-50">
                                            <i class="fas fa-toggle-{{ $category->is_active ? 'on' : 'off' }} text-xs"></i>
                                        </button>
                                        <button onclick="deleteCategory({{ $category->id }})" 
                                                class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- View All/Show Less Button -->
                        @if($totalCategories > $showLimit)
                            <div class="mt-3 text-center">
                                <button id="toggleViewBtn" onclick="toggleCategoriesView()" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-xs rounded-lg transition-all duration-300 flex items-center gap-2 mx-auto">
                                    <i class="fas fa-chevron-down transition-transform duration-300" id="toggleIcon"></i>
                                    <span id="toggleText">View All Categories ({{ $totalCategories }})</span>
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="col-span-full text-center py-4 text-gray-500">
                            <i class="fas fa-tags text-2xl text-gray-300 mb-1"></i>
                            <p class="text-xs">No categories added yet</p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- All Categories Dropdown -->
            <div id="allCategoriesDropdown" class="hidden mt-3 bg-gray-50 rounded-lg border border-gray-200 p-3">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-xs font-medium text-gray-700">All Categories</h4>
                    <button onclick="closeAllCategoriesDropdown()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
                <div id="allCategoriesList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-60 overflow-y-auto">
                    <!-- Categories will be populated here -->
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

<!-- Add Category Modal -->
<div id="addCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-4 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-bold text-gray-900">Add New Category</h3>
            <button onclick="closeAddCategoryModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="addCategoryForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="restaurant_id" value="{{ $restaurantId }}">
            
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                <input type="text" name="name" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                <textarea name="description" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Image (Optional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="category_image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                <span>Upload a file</span>
                                <input id="category_image" name="image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                <div id="image-preview" class="mt-2 hidden">
                    <img id="preview-img" class="h-20 w-20 object-cover rounded-md mx-auto">
                </div>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddCategoryModal()" 
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm text-white bg-green-600 rounded-md hover:bg-green-700">
                    Add Category
                </button>
            </div>
        </form>
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

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-4 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-bold text-gray-900">Edit Category</h3>
            <button onclick="closeEditCategoryModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editCategoryForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" id="edit_category_id">
            
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                <input type="text" name="name" id="edit_category_name" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                <textarea name="description" id="edit_category_description" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditCategoryModal()" 
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm text-white bg-green-600 rounded-md hover:bg-green-700">
                    Update Category
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

    // Category Management Functions
    function openAddCategoryModal() {
        document.getElementById('addCategoryModal').classList.remove('hidden');
        document.getElementById('addCategoryModal').classList.add('flex');
    }

    function closeAddCategoryModal() {
        document.getElementById('addCategoryModal').classList.add('hidden');
        document.getElementById('addCategoryModal').classList.remove('flex');
        document.getElementById('addCategoryForm').reset();
        document.getElementById('image-preview').classList.add('hidden');
    }

    function openEditCategoryModal(id, name, description) {
        document.getElementById('edit_category_id').value = id;
        document.getElementById('edit_category_name').value = name;
        document.getElementById('edit_category_description').value = description || '';
        document.getElementById('editCategoryModal').classList.remove('hidden');
        document.getElementById('editCategoryModal').classList.add('flex');
    }

    function closeEditCategoryModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
        document.getElementById('editCategoryModal').classList.remove('flex');
        document.getElementById('editCategoryForm').reset();
    }

    // Image preview functionality
    document.getElementById('category_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('image-preview').classList.add('hidden');
        }
    });

    // Add Category Form Submit
    document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("restaurant-categories.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                closeAddCategoryModal();
                location.reload(); // Reload to show new category
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('An error occurred. Please try again.', 'error');
        });
    });

    // Edit Category Form Submit
    document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const categoryId = document.getElementById('edit_category_id').value;
        
        fetch(`/restaurant-categories/${categoryId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-HTTP-Method-Override': 'PUT'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                closeEditCategoryModal();
                location.reload(); // Reload to show updated category
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('An error occurred. Please try again.', 'error');
        });
    });

    // Toggle Category Status
    function toggleCategory(id) {
        fetch(`/restaurant-categories/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                location.reload(); // Reload to show updated status
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('An error occurred. Please try again.', 'error');
        });
    }

    // Delete Category
    function deleteCategory(id) {
        if (confirm('Are you sure you want to delete this category?')) {
            fetch(`/restaurant-categories/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'DELETE'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    location.reload(); // Reload to show updated list
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('An error occurred. Please try again.', 'error');
            });
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

    // Category View Toggle Functions
    let isShowingAll = false;
    const showLimit = 6;

    function toggleCategoriesView() {
        const categoriesList = document.getElementById('categories-list');
        const categoryItems = document.querySelectorAll('.category-item');
        const searchBox = document.getElementById('category-search');
        const toggleBtn = document.getElementById('toggleViewBtn');
        const toggleIcon = document.getElementById('toggleIcon');
        const toggleText = document.getElementById('toggleText');
        const totalCategories = categoryItems.length;

        if (!isShowingAll) {
            // Show all categories with animation
            categoryItems.forEach((item, index) => {
                if (index >= showLimit) {
                    item.style.display = 'flex';
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(-10px)';
                    
                    setTimeout(() => {
                        item.style.transition = 'all 0.3s ease';
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, (index - showLimit) * 50); // Staggered animation
                }
            });

            // Show search box
            searchBox.classList.remove('hidden');
            searchBox.style.opacity = '0';
            searchBox.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                searchBox.style.transition = 'all 0.3s ease';
                searchBox.style.opacity = '1';
                searchBox.style.transform = 'translateY(0)';
            }, 100);

            // Update button
            toggleIcon.style.transform = 'rotate(180deg)';
            toggleText.textContent = `Show Less (${totalCategories})`;
            isShowingAll = true;

        } else {
            // Hide extra categories with animation
            categoryItems.forEach((item, index) => {
                if (index >= showLimit) {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(-10px)';
                    
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });

            // Hide search box
            searchBox.style.transition = 'all 0.3s ease';
            searchBox.style.opacity = '0';
            searchBox.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                searchBox.classList.add('hidden');
            }, 300);

            // Clear search
            document.getElementById('categorySearchInput').value = '';

            // Update button
            toggleIcon.style.transform = 'rotate(0deg)';
            toggleText.textContent = `View All Categories (${totalCategories})`;
            isShowingAll = false;
        }
    }

    // Search functionality
    function setupCategorySearch() {
        const searchInput = document.getElementById('categorySearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const categoryItems = document.querySelectorAll('.category-item');
                
                categoryItems.forEach(item => {
                    const categoryName = item.getAttribute('data-category-name');
                    const matches = categoryName.includes(searchTerm);
                    
                    if (matches) {
                        item.style.display = 'flex';
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    } else {
                        item.style.transition = 'all 0.2s ease';
                        item.style.opacity = '0.3';
                        item.style.transform = 'scale(0.95)';
                    }
                });
            });
        }
    }

    // All Categories Dropdown Functions
    function toggleAllCategoriesDropdown() {
        const dropdown = document.getElementById('allCategoriesDropdown');
        const icon = document.getElementById('allCategoryIcon');
        
        if (dropdown.classList.contains('hidden')) {
            // Show dropdown only
            populateAllCategoriesDropdown();
            dropdown.classList.remove('hidden');
            dropdown.style.opacity = '0';
            dropdown.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                dropdown.style.transition = 'all 0.3s ease';
                dropdown.style.opacity = '1';
                dropdown.style.transform = 'translateY(0)';
            }, 10);
            
            // Rotate icon
            icon.style.transform = 'rotate(180deg)';
        } else {
            // Hide dropdown
            closeAllCategoriesDropdown();
        }
    }

    function closeAllCategoriesDropdown() {
        const dropdown = document.getElementById('allCategoriesDropdown');
        const icon = document.getElementById('allCategoryIcon');
        
        // Hide dropdown only
        dropdown.style.transition = 'all 0.3s ease';
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            dropdown.classList.add('hidden');
        }, 300);
        
        // Rotate icon back
        icon.style.transform = 'rotate(0deg)';
    }

    function populateAllCategoriesDropdown() {
        const allCategoriesList = document.getElementById('allCategoriesList');
        const categoryItems = document.querySelectorAll('.category-item');
        
        // Clear existing content
        allCategoriesList.innerHTML = '';
        
        if (categoryItems.length === 0) {
            allCategoriesList.innerHTML = `
                <div class="col-span-full text-center py-4 text-gray-500">
                    <i class="fas fa-tags text-xl text-gray-300 mb-1"></i>
                    <p class="text-xs">No categories available</p>
                </div>
            `;
            return;
        }
        
        // Create dropdown items for each category
        categoryItems.forEach((item, index) => {
            const categoryName = item.querySelector('span.text-xs.font-medium').textContent;
            const statusBadge = item.querySelector('span.inline-flex');
            const isActive = statusBadge.classList.contains('bg-green-100');
            const description = item.querySelector('p.text-xs.text-gray-500');
            const descriptionText = description ? description.textContent : '';
            
            const dropdownItem = document.createElement('div');
            dropdownItem.className = 'flex items-center justify-between p-2 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors';
            dropdownItem.innerHTML = `
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium text-gray-900">${categoryName}</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium ${isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${isActive ? 'Active' : 'Inactive'}
                        </span>
                    </div>
                    ${descriptionText ? `<p class="text-xs text-gray-500 mt-1">${descriptionText}</p>` : ''}
                </div>
                <div class="flex items-center gap-1 ml-2">
                    <button onclick="scrollToCategory('${categoryName}')" 
                            class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50" 
                            title="View in main list">
                        <i class="fas fa-eye text-xs"></i>
                    </button>
                    <button onclick="editCategoryFromDropdown('${categoryName}', '${descriptionText}', ${categoryItems[index].getAttribute('data-category-id')})" 
                            class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50" 
                            title="Edit category">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button onclick="deleteCategoryFromDropdown('${categoryName}', ${categoryItems[index].getAttribute('data-category-id')})" 
                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50" 
                            title="Delete category">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            `;
            
            allCategoriesList.appendChild(dropdownItem);
        });
    }

    function scrollToCategory(categoryName) {
        // Close dropdown first
        closeAllCategoriesDropdown();
        
        // Find the category in main list
        const categoryItems = document.querySelectorAll('.category-item');
        categoryItems.forEach(item => {
            const itemName = item.querySelector('span.text-xs.font-medium').textContent;
            if (itemName === categoryName) {
                // Scroll to the category
                item.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                
                // Highlight the category briefly
                item.style.backgroundColor = '#fef3c7';
                item.style.borderColor = '#f59e0b';
                
                setTimeout(() => {
                    item.style.backgroundColor = '';
                    item.style.borderColor = '';
                }, 2000);
            }
        });
    }

    // Edit category from dropdown
    function editCategoryFromDropdown(categoryName, description, categoryId) {
        // Close dropdown first
        closeAllCategoriesDropdown();
        
        // Open edit modal with the category data
        openEditCategoryModal(categoryId, categoryName, description);
    }

    // Delete category from dropdown
    function deleteCategoryFromDropdown(categoryName, categoryId) {
        if (confirm(`Are you sure you want to delete the category "${categoryName}"?`)) {
            // Close dropdown first
            closeAllCategoriesDropdown();
            
            // Call the delete function
            deleteCategory(categoryId);
        }
    }



    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        setupCategorySearch();
        
        // Initially hide categories beyond the limit
        const categoryItems = document.querySelectorAll('.category-item');
        categoryItems.forEach((item, index) => {
            if (index >= showLimit) {
                item.style.display = 'none';
            }
        });
    });

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
