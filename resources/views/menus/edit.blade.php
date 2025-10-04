@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-3">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-4">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('menus.index', ['restaurant_id' => $menu->restaurant_id]) }}" 
                   class="hover:opacity-80 transition-opacity">
                    <i class="fas fa-arrow-left text-xl" style="color: #00d03c;"></i>
                </a>
                <h1 class="text-xl font-bold text-gray-900">Edit Menu Item</h1>
            </div>
            <p class="text-sm text-gray-600 ml-6">{{ $menu->name }}</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div id="success-message" class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                    <button onclick="hideSuccessMessage()" class="text-green-600 hover:text-green-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-4">
            <form action="{{ route('menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Row 1: Name, Category, Price, VAT Price, Currency (5 columns) -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $menu->name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                               placeholder="e.g., Chicken Biryani" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select id="category" name="category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror" required>
                            <option value="">Select Category</option>
                            @if($categories && $categories->count() > 0)
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ old('category', $menu->category) == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled selected>No categories available</option>
                            @endif
                        </select>
                        @if($categories && $categories->count() == 0)
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-info-circle"></i> 
                                No custom categories available. Using default categories.
                                <a href="{{ route('menus.index', ['restaurant_id' => $menu->restaurant_id]) }}" class="text-blue-600 hover:underline">Add categories in Menu Management</a>
                            </p>
                        @endif
                        @error('category')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $menu->price) }}" 
                               step="0.01" min="0" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror" 
                               placeholder="0.00" required>
                        @error('price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="vat_price" class="block text-sm font-medium text-gray-700 mb-1">VAT Price</label>
                        <input type="number" id="vat_price" name="vat_price" value="{{ old('vat_price', $menu->vat_price) }}" 
                               step="0.01" min="0" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vat_price') border-red-500 @enderror" 
                               placeholder="0.00">
                        @error('vat_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency *</label>
                        <select id="currency" name="currency" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('currency') border-red-500 @enderror" required>
                            <option value="GBP" {{ old('currency', $menu->currency ?? 'GBP') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="USD" {{ old('currency', $menu->currency ?? 'GBP') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency', $menu->currency ?? 'GBP') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="PKR" {{ old('currency', $menu->currency ?? 'GBP') == 'PKR' ? 'selected' : '' }}>PKR (₨)</option>
                        </select>
                        @error('currency')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Status, Availability, Spice Level, Preparation Time (4 columns) -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select id="status" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror" required>
                            <option value="active" {{ old('status', $menu->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $menu->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_available" value="1" 
                                       {{ old('is_available', $menu->is_available ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Available</span>
                            </label>
                        </div>
                        @error('is_available')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="spice_level" class="block text-sm font-medium text-gray-700 mb-1">Spice Level</label>
                        <select id="spice_level" name="spice_level" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('spice_level') border-red-500 @enderror">
                            <option value="0" {{ old('spice_level', $menu->spice_level ?? 0) == '0' ? 'selected' : '' }}>No Spice</option>
                            <option value="1" {{ old('spice_level', $menu->spice_level ?? 0) == '1' ? 'selected' : '' }}>Mild (1⭐)</option>
                            <option value="2" {{ old('spice_level', $menu->spice_level ?? 0) == '2' ? 'selected' : '' }}>Medium (2⭐)</option>
                            <option value="3" {{ old('spice_level', $menu->spice_level ?? 0) == '3' ? 'selected' : '' }}>Hot (3⭐)</option>
                            <option value="4" {{ old('spice_level', $menu->spice_level ?? 0) == '4' ? 'selected' : '' }}>Very Hot (4⭐)</option>
                            <option value="5" {{ old('spice_level', $menu->spice_level ?? 0) == '5' ? 'selected' : '' }}>Extreme (5⭐)</option>
                        </select>
                        @error('spice_level')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="preparation_time" class="block text-sm font-medium text-gray-700 mb-1">Prep Time (min)</label>
                        <input type="number" id="preparation_time" name="preparation_time" value="{{ old('preparation_time', $menu->preparation_time) }}" 
                               min="0" max="300"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('preparation_time') border-red-500 @enderror" 
                               placeholder="e.g., 15">
                        @error('preparation_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Calories and Tags (2 columns) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                    <div>
                        <label for="calories" class="block text-sm font-medium text-gray-700 mb-1">Calories (optional)</label>
                        <input type="number" id="calories" name="calories" value="{{ old('calories', $menu->calories) }}" 
                               min="0" max="5000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('calories') border-red-500 @enderror" 
                               placeholder="e.g., 350">
                        @error('calories')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags (optional)</label>
                        <input type="text" id="tags" name="tags" value="{{ old('tags', is_array($menu->tags) && count($menu->tags) > 0 ? implode(', ', $menu->tags) : '') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tags') border-red-500 @enderror" 
                               placeholder="e.g., Vegetarian, Gluten-Free, Spicy">
                        <p class="text-xs text-gray-500 mt-1">Separate with commas</p>
                        @error('tags')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 4: Description and Ingredients (2 columns) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" 
                                  placeholder="Brief description of the menu item...">{{ old('description', $menu->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-1">Ingredients</label>
                        <textarea id="ingredients" name="ingredients" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ingredients') border-red-500 @enderror" 
                                  placeholder="e.g., Chicken, Rice, Onions, Spices">{{ old('ingredients', $menu->ingredients) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Separate ingredients with commas</p>
                        @error('ingredients')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 5: Allergen and Dietary Flags (2 columns) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                    <div>
                        <label for="allergen" class="block text-sm font-medium text-gray-700 mb-1">Allergen (optional)</label>
                        <textarea id="allergen" name="allergen" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('allergen') border-red-500 @enderror" 
                                  placeholder="e.g., Contains Nuts, Dairy">{{ old('allergen', $menu->allergen) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Separate allergens with commas</p>
                        @error('allergen')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="dietary_flags" class="block text-sm font-medium text-gray-700 mb-1">Dietary Flags (optional)</label>
                        <textarea id="dietary_flags" name="dietary_flags" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('dietary_flags') border-red-500 @enderror" 
                                  placeholder="e.g., Vegan, Halal, Keto">{{ old('dietary_flags', is_array($menu->dietary_flags) && count($menu->dietary_flags) > 0 ? implode(', ', $menu->dietary_flags) : '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Separate flags with commas</p>
                        @error('dietary_flags')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 6: Current Image & New Image Upload -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    @if($menu->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $menu->image) }}" 
                                 alt="{{ $menu->name }}" 
                                 class="h-20 w-20 rounded-lg object-cover border">
                            <p class="text-sm text-gray-500 mt-1">Current image</p>
                        </div>
                    @else
                        <div class="mb-4">
                            <div class="h-20 w-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">No image uploaded</p>
                        </div>
                    @endif
                    
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Add More Images</label>
                    <input type="file" id="images" name="images[]" accept="image/*" multiple
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('images') border-red-500 @enderror @error('images.*') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB each). You can select multiple images.</p>
                    @error('images')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    
                    <!-- Image Preview Container -->
                    <div id="image-preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2 hidden">
                        <!-- Preview images will be added here -->
                    </div>
                </div>

                <!-- Row 4: Restaurant Addons (Full width) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Restaurant Addons</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-2">
                        @php
                            $selectedAddons = $menu->cold_drinks_addons ?? [];
                        @endphp
                        @forelse($restaurantAddons as $addon)
                            <div class="flex items-center">
                                <input type="checkbox" id="addon_{{ $addon->id }}" name="cold_drinks_addons[]" value="{{ $addon->id }}" 
                                       {{ in_array($addon->id, $selectedAddons) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="addon_{{ $addon->id }}" class="ml-2 text-sm text-gray-700 flex items-center gap-2">
                                    @if($addon->image)
                                        <img src="{{ asset('storage/' . $addon->image) }}" alt="{{ $addon->name }}" class="w-6 h-6 rounded-full object-cover">
                                    @else
                                        <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center">
                                            <i class="fas fa-glass-whiskey text-gray-400 text-xs"></i>
                                        </div>
                                    @endif
                                    {{ $addon->name }}
                                </label>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-4 text-gray-500">
                                <i class="fas fa-glass-whiskey text-2xl text-gray-300 mb-2"></i>
                                <p class="text-sm">No addons available</p>
                                <p class="text-xs text-gray-400">Add some using the "Add Addon" button above</p>
                            </div>
                        @endforelse
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Select addons that can be added to this menu item</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('menus.index', ['restaurant_id' => $menu->restaurant_id]) }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>Update Menu Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

    // Multiple image preview functionality
    document.getElementById('images').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.getElementById('image-preview');
        
        // Clear previous previews
        previewContainer.innerHTML = '';
        
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'relative group';
                        previewDiv.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}" 
                                 class="w-full h-20 object-cover rounded-lg border border-gray-200">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                <span class="text-white text-xs font-medium">${file.name}</span>
                            </div>
                        `;
                        previewContainer.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    });
</script>
@endsection
