@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-full overflow-x-hidden">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('customers.show', $customer) }}" class="text-[#00d03c] hover:text-[#00b833] transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h3 class="text-2xl font-bold text-gray-800">Order History</h3>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Customer:</span>
            <span class="font-semibold text-gray-700">{{ $customer->first_name }} {{ $customer->last_name }}</span>
        </div>
    </div>

    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalOrders ?? 28 }}</p>
                </div>
                <i class="fas fa-shopping-bag text-blue-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600">{{ $completedOrders ?? 24 }}</p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $pendingOrders ?? 3 }}</p>
                </div>
                <i class="fas fa-clock text-yellow-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Spent</p>
                    <p class="text-2xl font-bold text-purple-600">${{ $totalSpent ?? 1250 }}</p>
                </div>
                <i class="fas fa-dollar-sign text-purple-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Orders</label>
                <input type="text" placeholder="Order ID, Restaurant..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent text-sm">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="out_for_delivery">Out for Delivery</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent text-sm">
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button class="px-4 py-2 bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors text-sm">
                <i class="fas fa-search mr-2"></i>Search
            </button>
        </div>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @for($i = 1; $i <= 8; $i++)
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#00d03c] rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Order #{{ 1000 + $i }}</h4>
                            <p class="text-sm text-gray-600">{{ ['KFC', 'McDonald\'s', 'Pizza Hut', 'Subway', 'Burger King', 'Domino\'s', 'Taco Bell', 'Starbucks'][$i-1] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $i <= 2 ? 'bg-green-100 text-green-800' : ($i <= 4 ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $i <= 2 ? 'Delivered' : ($i <= 4 ? 'In Progress' : 'Confirmed') }}
                        </span>
                        <button class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-calendar"></i>
                            <span>{{ date('M d, Y', strtotime('-' . $i . ' days')) }}</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-clock"></i>
                            <span>{{ date('H:i', strtotime('-' . $i . ' hours')) }}</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Main Street, Apt {{ $i }}B</span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Items:</span> {{ 2 + ($i % 3) }} items
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Total:</span> ${{ 25.50 + ($i * 5) }}
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Payment:</span> {{ ['Credit Card', 'PayPal', 'Cash on Delivery'][($i-1) % 3] }}
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        @if($i <= 2)
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Delivered:</span> {{ date('M d, Y H:i', strtotime('-' . $i . ' days -2 hours')) }}
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Delivery Time:</span> 25 mins
                        </div>
                        @elseif($i <= 4)
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Estimated:</span> {{ date('H:i', strtotime('+1 hour')) }}
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Status:</span> {{ ['Preparing', 'Out for Delivery'][($i-3) % 2] }}
                        </div>
                        @else
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Estimated:</span> {{ date('H:i', strtotime('+45 minutes')) }}
                        </div>
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Status:</span> Confirmed
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-utensils"></i>
                            <span class="break-words">{{ ['Chicken Burger + Fries', 'Big Mac + Coke', 'Pepperoni Pizza', 'Sub Sandwich', 'Whopper + Onion Rings', 'Margherita Pizza', 'Taco Combo', 'Coffee + Muffin'][$i-1] }}</span>
                        </div>
                        <div class="flex items-center space-x-2 flex-wrap">
                            @if($i <= 2)
                            <button class="px-3 py-1 text-xs bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors">
                                <i class="fas fa-redo mr-1"></i>Reorder
                            </button>
                            <button class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-star mr-1"></i>Rate
                            </button>
                            @elseif($i <= 4)
                            <button class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                                <i class="fas fa-map-marker-alt mr-1"></i>Track
                            </button>
                            @else
                            <button class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-eye mr-1"></i>View
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>

    <!-- Order Analytics -->
    <div class="mt-6 bg-white rounded-lg shadow-md">
        <div class="p-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">Order Analytics</h4>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-chart-line text-blue-500 text-2xl"></i>
                    </div>
                    <h5 class="font-medium text-gray-800 mb-1">Average Order Value</h5>
                    <p class="text-2xl font-bold text-blue-600">${{ $avgOrderValue ?? 44.64 }}</p>
                    <p class="text-sm text-gray-600">Per order</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-heart text-green-500 text-2xl"></i>
                    </div>
                    <h5 class="font-medium text-gray-800 mb-1">Favorite Restaurant</h5>
                    <p class="text-lg font-bold text-green-600">KFC</p>
                    <p class="text-sm text-gray-600">8 orders</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-alt text-purple-500 text-2xl"></i>
                    </div>
                    <h5 class="font-medium text-gray-800 mb-1">Order Frequency</h5>
                    <p class="text-2xl font-bold text-purple-600">{{ $orderFrequency ?? 2.3 }}</p>
                    <p class="text-sm text-gray-600">Orders per week</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Order Trends Chart -->
    <div class="mt-6 bg-white rounded-lg shadow-md">
        <div class="p-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">Order Trends (Last 7 Days)</h4>
        </div>
        <div class="p-4">
            <div class="relative w-full h-64 md:h-80">
                <canvas id="orderTrendsChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Responsive Styles -->
<style>
@media (max-width: 768px) {
    .container {
        padding: 0.5rem;
        max-width: 100%;
        overflow-x: hidden;
    }
    
    .grid-cols-1.md\\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.5rem;
    }
    
    .grid-cols-1.md\\:grid-cols-3 {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .space-y-4 > div {
        margin-bottom: 0.75rem;
    }
    
    /* Fix mobile scroll issues */
    body {
        overflow-x: hidden;
    }
    
    .bg-white {
        margin: 0.25rem;
    }
    
    /* Mobile header adjustments */
    .flex.items-center.justify-between {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    /* Mobile search filters */
    .grid.grid-cols-1.md\\:grid-cols-4 {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    /* Mobile order cards */
    .grid.grid-cols-1.md\\:grid-cols-3 {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    /* Mobile button adjustments */
    .flex.items-center.space-x-2 {
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    /* Mobile text adjustments */
    .text-2xl {
        font-size: 1.5rem;
    }
    
    .text-lg {
        font-size: 1rem;
    }
    
    /* Mobile padding adjustments */
    .p-4 {
        padding: 0.75rem;
    }
    
    .p-6 {
        padding: 1rem;
    }
    
    /* Mobile margin adjustments */
    .mb-6 {
        margin-bottom: 1rem;
    }
    
    .mt-6 {
        margin-top: 1rem;
    }
    
    /* Mobile chart container */
    #orderTrendsChart {
        max-width: 100%;
        height: 200px !important;
    }
    
    /* Mobile table adjustments */
    .overflow-x-auto {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Mobile button text */
    .text-xs {
        font-size: 0.625rem;
    }
    
    /* Mobile icon adjustments */
    .text-2xl {
        font-size: 1.25rem;
    }
    
    /* Mobile space adjustments */
    .space-x-3 {
        gap: 0.5rem;
    }
    
    .space-x-2 {
        gap: 0.25rem;
    }
    
    /* Mobile flex adjustments */
    .flex.items-center.space-x-3 {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    /* Mobile grid adjustments */
    .grid.grid-cols-1.md\\:grid-cols-3.gap-6 {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0.25rem;
    }
    
    .grid-cols-1.md\\:grid-cols-4 {
        grid-template-columns: 1fr;
        gap: 0.25rem;
    }
    
    .p-4 {
        padding: 0.5rem;
    }
    
    .text-2xl {
        font-size: 1.25rem;
    }
    
    .text-lg {
        font-size: 0.875rem;
    }
    
    .mb-6 {
        margin-bottom: 0.75rem;
    }
    
    .mt-6 {
        margin-top: 0.75rem;
    }
    
    .space-y-4 > div {
        margin-bottom: 0.5rem;
    }
    
    .bg-white {
        margin: 0.125rem;
    }
}
</style>

<script>
// Order Trends Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('orderTrendsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Orders',
                data: [2, 1, 3, 2, 4, 3, 2],
                borderColor: '#00d03c',
                backgroundColor: 'rgba(0, 208, 60, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxTicksLimit: window.innerWidth < 768 ? 4 : 7
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});

// Search and filter functionality
function searchOrders() {
    // Search logic here
    console.log('Searching orders...');
}

// Reorder functionality
function reorder(orderId) {
    // Reorder logic here
    console.log('Reordering order:', orderId);
}

// Track order functionality
function trackOrder(orderId) {
    // Track order logic here
    console.log('Tracking order:', orderId);
}
</script>
@endsection
