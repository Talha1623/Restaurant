@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Addon</h1>
                    <p class="text-gray-600 mt-2">{{ $restaurant->business_name }} - {{ $addon->name }}</p>
                </div>
                <a href="{{ route('restaurants.addons.index', $restaurant->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Addons
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Addon Information</h3>
            </div>
            
            <form method="POST" action="{{ route('restaurants.addons.update', [$restaurant->id, $addon->id]) }}" 
                  enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <strong>Please fix the following errors:</strong>
                        </div>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Addon Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Addon Name *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $addon->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               placeholder="e.g., Coca Cola, Pepsi, Fresh Juice"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="md:col-span-1">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Price (Optional)
                        </label>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               value="{{ old('price', $addon->price) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('price') border-red-500 @enderror"
                               placeholder="e.g., 50.00">
                        @error('price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Image -->
                    @if($addon->image)
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Current Image
                            </label>
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('storage/' . $addon->image) }}" 
                                     alt="{{ $addon->name }}" 
                                     class="w-20 h-20 rounded-lg object-cover">
                                <div>
                                    <p class="text-sm text-gray-600">Current image</p>
                                    <p class="text-xs text-gray-500">Upload a new image below to replace it</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Image Upload -->
                    <div class="md:col-span-{{ $addon->image ? '2' : '1' }}">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $addon->image ? 'New Image (Optional)' : 'Image (Optional)' }}
                        </label>
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('image') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description (Optional)
                        </label>
                        <textarea 
                               id="description" 
                               name="description" 
                               rows="4"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                               placeholder="Enter addon description...">{{ old('description', $addon->description) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('restaurants.addons.index', $restaurant->id) }}" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-save mr-2"></i>
                        Update Addon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
