@extends('layouts.app')

@section('content')
@section('title', 'Restaurants')
<div class="space-y-4 max-w-full overflow-x-hidden">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <h1 class="text-lg md:text-xl font-bold text-gray-800 flex items-center gap-2">
            Restaurants Dashboard
        </h1>
         <div class="flex gap-2">
            <!-- Add New Restaurant -->
            <a href="{{ route('restaurants.create') }}" 
               class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md shadow hover:bg-green-700 transition">
                + Add New Restaurant
            </a>
        </div>
    </div>

    <!-- 📊 Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4">
        <!-- Total -->
        <div class="p-3 md:p-4 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg shadow flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium opacity-80">Total</h3>
                <p class="text-lg md:text-2xl font-bold">{{ $totalRestaurants }}</p>
            </div>
            <i class="fas fa-store text-lg md:text-2xl"></i>
        </div>

        <!-- Active -->
        <div class="p-3 md:p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium opacity-80 flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> Active
                </h3>
                <p class="text-lg md:text-2xl font-bold">{{ $activeRestaurants }}</p>
            </div>
            <i class="fas fa-check text-lg md:text-2xl"></i>
        </div>

        <!-- Inactive -->
        <div class="p-3 md:p-4 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-lg shadow flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium opacity-80 flex items-center gap-1">
                    <i class="fas fa-times-circle"></i> Inactive
                </h3>
                <p class="text-lg md:text-2xl font-bold">{{ $inactiveRestaurants }}</p>
            </div>
            <i class="fas fa-times text-lg md:text-2xl"></i>
        </div>

        <!-- Extra: Cuisines -->
        <div class="p-3 md:p-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg shadow flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium opacity-80"><i class="fas fa-utensils"></i> Cuisines</h3>
                <p class="text-lg md:text-2xl font-bold">{{ $cuisines }}</p>
            </div>
            <i class="fas fa-bowl-food text-lg md:text-2xl"></i>
        </div>
    </div>

    <!-- Flash -->
    @if(session('success'))
        <div class="p-3 bg-green-100 text-green-700 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" class="flex flex-col gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search restaurants..." 
               class="w-full px-3 py-2 text-sm border rounded-md focus:ring focus:ring-green-200">

        <div class="flex flex-wrap gap-2">
            <select name="status" class="flex-1 min-w-0 px-2 py-1 text-xs border rounded-md focus:ring-2 focus:ring-indigo-400">
                <option value="">Status</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
            </select>
            <select name="cuisine_tags" class="flex-1 min-w-0 px-2 py-1 text-xs border rounded-md focus:ring-2 focus:ring-indigo-400">
                <option value="">Cuisine</option>
                <option value="Italian" {{ request('cuisine_tags')=='Italian'?'selected':'' }}>Italian</option>
                <option value="Chinese" {{ request('cuisine_tags')=='Chinese'?'selected':'' }}>Chinese</option>
                <option value="Fast Food" {{ request('cuisine_tags')=='Fast Food'?'selected':'' }}>Fast Food</option>
                <option value="Desi" {{ request('cuisine_tags')=='Desi'?'selected':'' }}>Desi</option>
            </select>
            <select name="sort" class="flex-1 min-w-0 px-2 py-1 text-xs border rounded-md focus:ring-2 focus:ring-indigo-400">
                <option value="">Sort</option>
                <option value="latest" {{ request('sort')=='latest'?'selected':'' }}>Latest</option>
                <option value="oldest" {{ request('sort')=='oldest'?'selected':'' }}>Oldest</option>
                <option value="az" {{ request('sort')=='az'?'selected':'' }}>A-Z</option>
                <option value="za" {{ request('sort')=='za'?'selected':'' }}>Z-A</option>
            </select>
            <button type="submit" class="px-3 py-1 bg-indigo-600 text-white text-xs rounded-md hover:bg-indigo-700 shadow">
                Filter
            </button>
        </div>
    </form>

<!-- 📋 Table - Desktop View -->
<div class="hidden md:block bg-white shadow rounded-lg overflow-hidden border border-gray-200">
    <table class="min-w-full border-collapse">
        <thead class="bg-gray-100 border-b">
            <tr class="text-gray-600 text-xs text-center">
                <th class="px-2 py-2 border-r">ID</th>
                <th class="px-2 py-2 border-r">Restaurant</th>
                <th class="px-2 py-2 border-r">Contact</th>
                <th class="px-2 py-2 border-r">Phone</th>
                <th class="px-2 py-2 border-r">City</th>
                <th class="px-2 py-2 border-r">Cuisine</th>
                <th class="px-2 py-2 border-r">Status</th>
                <th class="px-2 py-2 border-r">Block</th>
                <th class="px-2 py-2">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-xs">
            @forelse($restaurants as $restaurant)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-2 py-2 border-r text-center font-medium text-gray-700">{{ $restaurant->id }}</td>
                <td class="px-2 py-2 border-r font-semibold text-indigo-600">
                    <a href="{{ route('restaurants.show',$restaurant) }}" class="hover:underline">{{ $restaurant->legal_name ?? 'N/A' }}</a>
                </td>
                <td class="px-2 py-2 border-r">{{ $restaurant->contact_person ?? 'N/A' }}</td>
                <td class="px-2 py-2 border-r">{{ $restaurant->phone ?? 'N/A' }}</td>
                <td class="px-2 py-2 border-r">{{ $restaurant->city ?? 'N/A' }}</td>
                <td class="px-2 py-2 border-r">{{ $restaurant->cuisine_tags ?? 'N/A' }}</td>
                <td class="px-2 py-2 border-r text-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" 
                               class="sr-only peer" 
                               data-restaurant-id="{{ $restaurant->id }}"
                               {{ $restaurant->status == 'active' ? 'checked' : '' }}
                               onchange="toggleStatus({{ $restaurant->id }}, this.checked)">
                        <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </td>
                <td class="px-2 py-2 border-r text-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" 
                               class="sr-only peer" 
                               data-restaurant-id="{{ $restaurant->id }}"
                               {{ $restaurant->blocked ? 'checked' : '' }}
                               onchange="toggleBlock({{ $restaurant->id }}, this.checked)">
                        <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </td>
                <td class="px-2 py-2 text-right space-x-1 flex justify-end">
                    <!-- View -->
                    <a href="{{ route('restaurants.show',$restaurant) }}" class="inline-flex items-center px-1.5 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 shadow">
                        <i class="fas fa-eye"></i>
                    </a>

                    <!-- Edit -->
                    <a href="{{ route('restaurants.edit',$restaurant) }}" class="inline-flex items-center px-1.5 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700 shadow">
                        <i class="fas fa-edit"></i>
                    </a>

                    <!-- Delete -->
                    <form action="{{ route('restaurants.destroy',$restaurant) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete this restaurant?')" 
                            class="inline-flex items-center px-1.5 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 shadow">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-4 py-4 text-center text-gray-500">No restaurants found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- 📱 Mobile Cards View -->
<div class="md:hidden space-y-3">
    @forelse($restaurants as $restaurant)
    <div class="bg-white shadow rounded-lg border border-gray-200 p-3">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-store text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">{{ $restaurant->legal_name ?? 'N/A' }}</h3>
                    <p class="text-xs text-gray-600">ID: {{ $restaurant->id }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-1">
                @if($restaurant->status=='active')
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                        <i class="fas fa-check-circle"></i> Active
                    </span>
                @else
                    <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-700">
                        <i class="fas fa-times-circle"></i> Inactive
                    </span>
                @endif
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-2 mb-3 text-xs text-gray-600">
            <div class="flex items-center space-x-1">
                <i class="fas fa-user-tie w-3"></i>
                <span class="truncate">{{ $restaurant->contact_person ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center space-x-1">
                <i class="fas fa-phone w-3"></i>
                <span class="truncate">{{ $restaurant->phone ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center space-x-1">
                <i class="fas fa-map-marker-alt w-3"></i>
                <span class="truncate">{{ $restaurant->city ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center space-x-1">
                <i class="fas fa-utensils w-3"></i>
                <span class="truncate">{{ $restaurant->cuisine_tags ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="flex items-center justify-between">
            <a href="{{ route('restaurants.show',$restaurant) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                View Details
            </a>
            <div class="flex items-center space-x-1">
                <!-- View -->
                <a href="{{ route('restaurants.show',$restaurant) }}" class="inline-flex items-center px-1.5 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 shadow">
                    <i class="fas fa-eye"></i>
                </a>

                <!-- Edit -->
                <a href="{{ route('restaurants.edit',$restaurant) }}" class="inline-flex items-center px-1.5 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700 shadow">
                    <i class="fas fa-edit"></i>
                </a>

                <!-- Delete -->
                <form action="{{ route('restaurants.destroy',$restaurant) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Delete this restaurant?')" 
                        class="inline-flex items-center px-1.5 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 shadow">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white shadow rounded-lg border border-gray-200 p-6 text-center">
        <i class="fas fa-store text-3xl text-gray-400 mb-3"></i>
        <p class="text-gray-500 text-sm">No restaurants found</p>
    </div>
    @endforelse
</div>


    <!-- Pagination -->
    <div class="mt-4">{{ $restaurants->links() }}</div>
</div>

<!-- Mobile Responsive Styles -->
<style>
@media (max-width: 768px) {
    .space-y-6 {
        gap: 1rem;
    }
    
    .space-y-4 {
        gap: 0.75rem;
    }
    
    /* Mobile header adjustments */
    .flex.flex-col.md\\:flex-row {
        gap: 0.75rem;
    }
    
    /* Mobile stats grid */
    .grid.grid-cols-2.md\\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
    }
    
    /* Mobile filter adjustments */
    .flex.flex-wrap.gap-2 {
        gap: 0.5rem;
    }
    
    .flex-1.min-w-0 {
        min-width: 0;
        flex: 1;
    }
    
    /* Mobile card adjustments */
    .space-y-2 {
        gap: 0.5rem;
    }
    
    .space-x-2 {
        gap: 0.5rem;
    }
    
    .space-x-3 {
        gap: 0.75rem;
    }
    
    /* Mobile text adjustments */
    .text-xl.md\\:text-2xl {
        font-size: 1.25rem;
    }
    
    .text-xs.md\\:text-sm {
        font-size: 0.75rem;
    }
    
    .text-xl.md\\:text-3xl {
        font-size: 1.25rem;
    }
    
    .text-2xl.md\\:text-4xl {
        font-size: 1.5rem;
    }
    
    /* Mobile padding adjustments */
    .p-4.md\\:p-6 {
        padding: 1rem;
    }
    
    /* Mobile margin adjustments */
    .mb-3 {
        margin-bottom: 0.75rem;
    }
    
    .mb-4 {
        margin-bottom: 1rem;
    }
    
    /* Mobile button adjustments */
    .px-2.py-1 {
        padding: 0.25rem 0.5rem;
    }
    
    .px-3.py-1 {
        padding: 0.25rem 0.75rem;
    }
    
    /* Mobile icon adjustments */
    .w-4 {
        width: 1rem;
    }
    
    .w-10.h-10 {
        width: 2.5rem;
        height: 2.5rem;
    }
    
    /* Mobile truncate */
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Mobile flex adjustments */
    .flex.items-center.space-x-2 {
        gap: 0.5rem;
    }
    
    .flex.items-center.space-x-3 {
        gap: 0.75rem;
    }
}

@media (max-width: 480px) {
    .grid.grid-cols-2.md\\:grid-cols-4 {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .p-4.md\\:p-6 {
        padding: 0.75rem;
    }
    
    .text-xl.md\\:text-2xl {
        font-size: 1.125rem;
    }
    
    .text-xl.md\\:text-3xl {
        font-size: 1.125rem;
    }
    
    .text-2xl.md\\:text-4xl {
        font-size: 1.25rem;
    }
    
    .space-y-6 {
        gap: 0.75rem;
    }
    
    .space-y-4 {
        gap: 0.5rem;
    }
    
    .mb-3 {
        margin-bottom: 0.5rem;
    }
    
    .mb-4 {
        margin-bottom: 0.75rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
function toggleStatus(restaurantId, isActive) {
    const status = isActive ? 'active' : 'inactive';
    
    // Show loading state
    const toggle = document.querySelector(`input[data-restaurant-id="${restaurantId}"]`);
    toggle.disabled = true;
    
    // Send AJAX request
    fetch(`/restaurants/${restaurantId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Status updated successfully!', 'success');
            
            // Reload the page to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            // Revert toggle if failed
            toggle.checked = !isActive;
            showNotification('Failed to update status!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert toggle if failed
        toggle.checked = !isActive;
        showNotification('Error updating status!', 'error');
    })
    .finally(() => {
        toggle.disabled = false;
    });
}

function toggleBlock(restaurantId, isBlocked) {
    // Show loading state
    const toggle = document.querySelector(`input[data-restaurant-id="${restaurantId}"][onchange*="toggleBlock"]`);
    toggle.disabled = true;
    
    // Send AJAX request
    fetch(`/restaurants/${restaurantId}/toggle-block`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ blocked: isBlocked })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Block status updated successfully!', 'success');
            
            // Reload the page to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            // Revert toggle if failed
            toggle.checked = !isBlocked;
            showNotification('Failed to update block status!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert toggle if failed
        toggle.checked = !isBlocked;
        showNotification('Error updating block status!', 'error');
    })
    .finally(() => {
        toggle.disabled = false;
    });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
