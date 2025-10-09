@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('menus.index', ['restaurant_id' => $menu->restaurant_id]) }}" 
                   class="hover:opacity-80 transition-opacity">
                    <i class="fas fa-arrow-left text-2xl" style="color: #00d03c;"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Menu Item Details</h1>
                    <p class="text-gray-600 mt-1">{{ $menu->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('menus.edit', $menu) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>

        <!-- Menu Details -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Image Section -->
            @if($menu->images && count($menu->images) > 0)
                <div class="h-64 bg-gray-200">
                    <img src="{{ asset('storage/' . $menu->images->first()->image_url) }}" 
                         alt="{{ $menu->name }}" 
                         class="w-full h-full object-cover">
                </div>
            @elseif($menu->image)
                <div class="h-64 bg-gray-200">
                    <img src="{{ asset('storage/' . $menu->image) }}" 
                         alt="{{ $menu->name }}" 
                         class="w-full h-full object-cover">
                </div>
            @else
                <div class="h-64 bg-gray-200 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-image text-6xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">No image available</p>
                    </div>
                </div>
            @endif

            <!-- Additional Images Section -->
            @if($menu->images && count($menu->images) > 1)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Additional Images</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($menu->images->skip(1) as $image)
                            <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $image->image_url) }}" 
                                     alt="{{ $menu->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Details Section -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Item Name</label>
                                <p class="text-lg text-gray-900">{{ $menu->name }}</p>
                            </div>
                            
                            @php
                                $resolvedCategory = null;
                                try {
                                    if (!empty($menu->category_id)) {
                                        $resolvedCategory = $menu->category()->first();
                                    }
                                } catch (\Throwable $e) {
                                    $resolvedCategory = null;
                                }
                            @endphp

                            @if($resolvedCategory || !empty($menu->category))
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Category *</label>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($resolvedCategory && !empty($resolvedCategory->image))
                                            <img src="{{ asset('storage/' . $resolvedCategory->image) }}" 
                                                 alt="{{ $resolvedCategory->name }}" 
                                                 class="w-10 h-10 rounded-lg object-cover">
                                        @endif
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $resolvedCategory ? $resolvedCategory->name : $menu->category }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            
                            @if($menu->secondFlavor)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Second Flavor</label>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($menu->secondFlavor->image)
                                            <img src="{{ asset('storage/' . $menu->secondFlavor->image) }}" 
                                                 alt="{{ $menu->secondFlavor->name }}" 
                                                 class="w-10 h-10 rounded-lg object-cover">
                                        @endif
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            {{ $menu->secondFlavor->name }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Price</label>
                                <p class="text-2xl font-bold text-green-600">£{{ number_format($menu->price, 2) }}</p>
                            </div>
                            
                            @if($menu->vat_price)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">VAT Price</label>
                                    <p class="text-xl font-semibold text-orange-600">£{{ number_format($menu->vat_price, 2) }}</p>
                                </div>
                            @endif
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $menu->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($menu->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Restaurant Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Restaurant Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Restaurant</label>
                                <p class="text-lg text-gray-900">{{ $menu->restaurant->legal_name }}</p>
                                <p class="text-sm text-gray-600">{{ $menu->restaurant->business_name }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Contact Person</label>
                                <p class="text-gray-900">{{ $menu->restaurant->contact_person }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <p class="text-gray-900">{{ $menu->restaurant->email }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Phone</label>
                                <p class="text-gray-900">{{ $menu->restaurant->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($menu->description)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $menu->description }}</p>
                    </div>
                @endif

                <!-- Ingredients -->
                @if($menu->ingredients)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Ingredients</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $menu->ingredients) as $ingredient)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ trim($ingredient) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Allergen and Dietary Information -->
                @if($menu->allergen || ($menu->dietary_flags && count($menu->dietary_flags) > 0))
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Allergen & Dietary Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($menu->allergen)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">⚠️ Allergen Information</h4>
                                    <p class="text-red-600 font-medium">{{ $menu->allergen }}</p>
                                </div>
                            @endif
                            
                            @if($menu->dietary_flags && count($menu->dietary_flags) > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">🥗 Dietary Flags</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($menu->dietary_flags as $flag)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ $flag }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                No image available

<!-- Restaurant Addons -->
                @if($menu->cold_drinks_addons && count($menu->cold_drinks_addons) > 0)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Restaurant Addons</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @php
                                $selectedAddons = \App\Models\RestaurantAddon::whereIn('id', $menu->cold_drinks_addons)->get();
                            @endphp
                            @foreach($selectedAddons as $addon)
                                <div class="flex items-center justify-between p-2 bg-blue-50 rounded-lg">
                                    <div class="flex items-center">
                                        @if($addon->image)
                                            <img src="{{ asset('storage/' . $addon->image) }}" alt="{{ $addon->name }}" class="w-8 h-8 rounded-full mr-3 object-cover">
                                        @else
                                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-plus text-gray-400 text-sm"></i>
                                            </div>
                                        @endif
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-700">{{ $addon->name }}</span>
                                            @if($addon->description)
                                                <span class="text-xs text-gray-500 line-clamp-1">{{ $addon->description }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($addon->price)
                                        <span class="text-sm font-semibold text-green-600">Rs. {{ number_format($addon->price, 2) }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                        <div>
                            <label class="font-medium">Created</label>
                            <p>{{ $menu->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <label class="font-medium">Last Updated</label>
                            <p>{{ $menu->updated_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex items-center justify-between">
            <form action="{{ route('menus.destroy', $menu) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this menu item?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-trash mr-2"></i>Delete Menu Item
                </button>
            </form>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('menus.edit', $menu) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>Edit Menu Item
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
