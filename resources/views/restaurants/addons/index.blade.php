@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Restaurant Addons</h1>
                    <p class="text-gray-600 mt-2">{{ $restaurant->business_name }} - Manage your addons</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('restaurants.show', $restaurant->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Restaurant
                    </a>
                    <a href="{{ route('restaurants.addons.create', $restaurant->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Addon
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Addons List -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Current Addons</h3>
            </div>
            
            @if($addons->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($addons as $addon)
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4 flex-1">
                                    @if($addon->image)
                                        <img src="{{ asset('storage/' . $addon->image) }}" 
                                             alt="{{ $addon->name }}" 
                                             class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-glass-whiskey text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $addon->name }}</h4>
                                            @if($addon->price)
                                                <span class="px-2 py-1 text-sm font-semibold bg-green-100 text-green-800 rounded-md">
                                                    Rs. {{ number_format($addon->price, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($addon->description)
                                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $addon->description }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">Created {{ $addon->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4 flex-shrink-0 ml-4">
                                    <span class="px-3 py-1 text-xs rounded-full {{ $addon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $addon->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('restaurants.addons.edit', [$restaurant->id, $addon->id]) }}" 
                                           class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('restaurants.addons.toggle', [$restaurant->id, $addon->id]) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 text-yellow-600 hover:bg-yellow-100 rounded-lg transition"
                                                    title="{{ $addon->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-toggle-{{ $addon->is_active ? 'on' : 'off' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('restaurants.addons.destroy', [$restaurant->id, $addon->id]) }}" 
                                              class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this addon?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-glass-whiskey text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No addons found</h3>
                    <p class="text-gray-500 mb-6">Get started by adding your first addon.</p>
                    <a href="{{ route('restaurants.addons.create', $restaurant->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i>
                        Add Your First Addon
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
