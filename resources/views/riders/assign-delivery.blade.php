@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('riders.show', $rider) }}" class="text-[#00d03c] hover:text-[#00b833] transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h3 class="text-2xl font-bold text-gray-800">Assign Delivery</h3>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Rider:</span>
            <span class="font-semibold text-gray-700">{{ $rider->name }}</span>
        </div>
    </div>

    <!-- Delivery Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Available Deliveries</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $availableDeliveries ?? 12 }}</p>
                </div>
                <i class="fas fa-box text-blue-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Today's Deliveries</p>
                    <p class="text-2xl font-bold text-green-600">{{ $todayDeliveries ?? 8 }}</p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending Assignments</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $pendingAssignments ?? 4 }}</p>
                </div>
                <i class="fas fa-clock text-yellow-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Earnings</p>
                    <p class="text-2xl font-bold text-purple-600">${{ $totalEarnings ?? 156.50 }}</p>
                </div>
                <i class="fas fa-dollar-sign text-purple-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Search deliveries..." class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent">
                    <option value="">All Areas</option>
                    <option value="downtown">Downtown</option>
                    <option value="uptown">Uptown</option>
                    <option value="suburbs">Suburbs</option>
                </select>
                
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent">
                    <option value="">All Priority</option>
                    <option value="high">High Priority</option>
                    <option value="medium">Medium Priority</option>
                    <option value="low">Low Priority</option>
                </select>
            </div>
            
            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors">
                    <i class="fas fa-plus mr-2"></i>Assign New
                </button>
                <button class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Available Deliveries -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Delivery List -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Available Deliveries</h4>
            </div>
            
            <div class="p-4 space-y-4">
                @for($i = 1; $i <= 5; $i++)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-utensils text-blue-600"></i>
                            </div>
                            <div>
                                <h5 class="font-semibold text-gray-800">Order #{{ 1000 + $i }}</h5>
                                <p class="text-sm text-gray-600">KFC Restaurant</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $i <= 2 ? 'bg-red-100 text-red-800' : ($i <= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ $i <= 2 ? 'High' : ($i <= 3 ? 'Medium' : 'Low') }} Priority
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <p class="text-sm text-gray-600">Customer</p>
                            <p class="font-medium text-gray-800">John Doe</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Amount</p>
                            <p class="font-medium text-gray-800">${{ 25.50 + ($i * 5) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Distance</p>
                            <p class="font-medium text-gray-800">{{ 2.5 + ($i * 0.5) }} km</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Est. Time</p>
                            <p class="font-medium text-gray-800">{{ 15 + ($i * 5) }} min</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Downtown Area</span>
                        </div>
                        <button class="px-4 py-2 bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors text-sm">
                            <i class="fas fa-plus mr-1"></i>Assign
                        </button>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Delivery Details & Map -->
        <div class="space-y-6">
            <!-- Selected Delivery Details -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800">Delivery Details</h4>
                </div>
                
                <div class="p-4">
                    <div class="mb-4">
                        <h5 class="font-semibold text-gray-800 mb-2">Order Information</h5>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Order ID:</span>
                                <span class="font-medium">#1001</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Restaurant:</span>
                                <span class="font-medium">KFC Restaurant</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Customer:</span>
                                <span class="font-medium">John Doe</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Phone:</span>
                                <span class="font-medium">+1 234 567 8900</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Amount:</span>
                                <span class="font-bold text-[#00d03c]">$30.50</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="font-semibold text-gray-800 mb-2">Delivery Address</h5>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-sm text-gray-700">123 Main Street, Apt 4B</p>
                            <p class="text-sm text-gray-700">Downtown, City 12345</p>
                            <p class="text-sm text-gray-600 mt-1">Special Instructions: Ring doorbell twice</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="font-semibold text-gray-800 mb-2">Items</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-sm">
                                <span>Chicken Burger</span>
                                <span>2x</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span>French Fries</span>
                                <span>1x</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span>Soft Drink</span>
                                <span>2x</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <button class="flex-1 px-4 py-2 bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors">
                            <i class="fas fa-check mr-2"></i>Accept Delivery
                        </button>
                        <button class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Route & Navigation -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-800">Route & Navigation</h4>
                </div>
                
                <div class="p-4">
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Estimated Distance:</span>
                            <span class="font-medium">3.2 km</span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Estimated Time:</span>
                            <span class="font-medium">18 minutes</span>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-600">Traffic Status:</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Light Traffic</span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <button class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fas fa-route mr-2"></i>Get Directions
                        </button>
                        <button class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-map-marker-alt mr-2"></i>Track Location
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Assignments -->
    <div class="mt-6 bg-white rounded-lg shadow-md">
        <div class="p-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">Recent Assignments</h4>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Restaurant</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @for($i = 1; $i <= 5; $i++)
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ 1000 + $i }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">Customer {{ $i }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">Restaurant {{ $i }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">${{ 25.50 + ($i * 5) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $i <= 2 ? 'bg-green-100 text-green-800' : ($i <= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $i <= 2 ? 'Completed' : ($i <= 3 ? 'In Progress' : 'Pending') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-[#00d03c] hover:text-[#00b833] mr-3">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Mobile Responsive Styles -->
<style>
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .grid-cols-1.md\\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .grid-cols-1.lg\\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .flex-col.md\\:flex-row {
        flex-direction: column;
    }
    
    .space-y-4.md\\:space-y-0 {
        margin-bottom: 1rem;
    }
    
    .w-full.md\\:w-64 {
        width: 100%;
    }
    
    .overflow-x-auto {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    table {
        min-width: 600px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh delivery list every 30 seconds
    setInterval(function() {
        // Add refresh logic here
        console.log('Refreshing delivery list...');
    }, 30000);
    
    // Handle delivery assignment
    document.querySelectorAll('.assign-delivery-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            // Add assignment logic here
            console.log('Assigning delivery:', orderId);
        });
    });
    
    // Handle route optimization
    document.querySelectorAll('.get-directions-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Add navigation logic here
            console.log('Getting directions...');
        });
    });
});
</script>
@endsection
