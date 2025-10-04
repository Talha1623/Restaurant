@extends('layouts.app')

@section('title', $restaurant->name . ' - Orders')
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
                    {{ $restaurant->name }} - Orders
                </h3>
                <p class="text-gray-600 text-sm mt-1">Manage and track all restaurant orders</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-2">
                <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-1">
                    <i class="fas fa-plus"></i> New Order
                </button>
                <button class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
    </div>

    <!-- Orders Statistics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-800">156</p>
                    <p class="text-xs text-green-600">+12% from last week</p>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-shopping-bag text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Today's Orders -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Today's Orders</p>
                    <p class="text-2xl font-bold text-gray-800">23</p>
                    <p class="text-xs text-green-600">+3 from yesterday</p>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-calendar-day text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">8</p>
                    <p class="text-xs text-orange-600">Avg: 15 min</p>
                </div>
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-clock text-orange-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Today's Revenue</p>
                    <p class="text-2xl font-bold text-gray-800">PKR 1,247</p>
                    <p class="text-xs text-green-600">+8% from yesterday</p>
                </div>
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-pound-sign text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Analytics Chart -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Order Trends (Last 7 Days)</h3>
            <div class="flex gap-2">
                <button class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg">7 Days</button>
                <button class="px-3 py-1 border border-gray-300 text-sm rounded-lg hover:bg-gray-50">30 Days</button>
                <button class="px-3 py-1 border border-gray-300 text-sm rounded-lg hover:bg-gray-50">3 Months</button>
            </div>
        </div>
        <div class="h-64">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>

    <!-- Popular Items & Peak Hours -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Popular Items -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Items Today</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hamburger text-orange-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Fish & Chips</p>
                            <p class="text-sm text-gray-600">PKR 12.99</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">12 orders</p>
                        <p class="text-sm text-green-600">+3 today</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-pepper-hot text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Chicken Tikka Masala</p>
                            <p class="text-sm text-gray-600">PKR 14.99</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">8 orders</p>
                        <p class="text-sm text-green-600">+2 today</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-drumstick-bite text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Sunday Roast Beef</p>
                            <p class="text-sm text-gray-600">PKR 18.50</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">6 orders</p>
                        <p class="text-sm text-green-600">+1 today</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peak Hours -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Peak Hours Today</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sun text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">12:00 PM - 2:00 PM</p>
                            <p class="text-sm text-gray-600">Lunch Rush</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">15 orders</p>
                        <p class="text-sm text-blue-600">Peak time</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-moon text-orange-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">7:00 PM - 9:00 PM</p>
                            <p class="text-sm text-gray-600">Dinner Rush</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">12 orders</p>
                        <p class="text-sm text-orange-600">Busy time</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-gray-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">3:00 PM - 5:00 PM</p>
                            <p class="text-sm text-gray-600">Afternoon</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">4 orders</p>
                        <p class="text-sm text-gray-600">Quiet time</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" placeholder="Search orders by ID or customer name..." 
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="flex flex-wrap gap-2">
                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>Preparing</option>
                    <option>Ready</option>
                    <option>Delivered</option>
                    <option>Cancelled</option>
                </select>
                
                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    <option>All Time</option>
                    <option>Today</option>
                    <option>This Week</option>
                    <option>This Month</option>
                </select>
                
                <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                <label for="selectAll" class="text-sm font-medium text-gray-700">Select All</label>
                <span class="text-sm text-gray-500">(0 selected)</span>
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
                    <i class="fas fa-check"></i> Mark as Ready
                </button>
                <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-1">
                    <i class="fas fa-truck"></i> Assign Delivery
                </button>
                <button class="px-3 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition flex items-center gap-1">
                    <i class="fas fa-print"></i> Print Receipts
                </button>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table Header -->
        <div class="hidden lg:block bg-gray-50 px-6 py-3 border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-600">
                <div class="col-span-1">Select</div>
                <div class="col-span-2">Order ID</div>
                <div class="col-span-2">Customer</div>
                <div class="col-span-2">Items</div>
                <div class="col-span-1">Amount</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Time</div>
                <div class="col-span-2">Actions</div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="divide-y divide-gray-200">
            @foreach($orders as $order)
            <!-- Desktop View -->
            <div class="hidden lg:block px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-1">
                        <input type="checkbox" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    </div>
                    <div class="col-span-2">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-800">#{{ $order['id'] }}</span>
                            @if($order['status'] === 'Pending')
                                <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">{{ $order['payment_status'] }}</p>
                    </div>
                    <div class="col-span-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $order['customer'] }}</p>
                                <p class="text-xs text-gray-500">{{ $order['phone'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-800">{{ $order['items'] }}</p>
                        <p class="text-xs text-gray-500">{{ $order['quantity'] }} items</p>
                        @if(isset($order['special_instructions']))
                            <p class="text-xs text-orange-600"><i class="fas fa-exclamation-triangle"></i> Special: {{ $order['special_instructions'] }}</p>
                        @endif
                    </div>
                    <div class="col-span-1">
                        <span class="font-semibold text-gray-800">PKR {{ $order['amount'] }}</span>
                    </div>
                    <div class="col-span-1">
                        <span class="px-2 py-1 text-xs rounded-full {{ $order['status_class'] }}">
                            {{ $order['status'] }}
                        </span>
                        @if($order['status'] === 'Pending')
                            <p class="text-xs text-orange-600 mt-1">{{ $order['time'] }}</p>
                        @else
                            <p class="text-xs text-gray-500 mt-1">{{ $order['time'] }}</p>
                        @endif
                    </div>
                    <div class="col-span-1">
                        <div class="flex items-center gap-1">
                            <i class="fas fa-clock text-gray-400 text-xs"></i>
                            <span class="text-sm text-gray-600">{{ $order['time'] }}</span>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="flex gap-1">
                            <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition" title="Update Status">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="px-2 py-1 bg-purple-500 text-white text-xs rounded hover:bg-purple-600 transition" title="Contact Customer">
                                <i class="fas fa-phone"></i>
                            </button>
                            <button class="px-2 py-1 bg-orange-500 text-white text-xs rounded hover:bg-orange-600 transition" title="Print Receipt">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile View -->
            <div class="lg:hidden p-4 border-b border-gray-200">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <div>
                            <h4 class="font-semibold text-gray-800">Order #{{ $order['id'] }}</h4>
                            <p class="text-sm text-gray-600">{{ $order['time'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs rounded-full {{ $order['status_class'] }}">
                            {{ $order['status'] }}
                        </span>
                        @if($order['status'] === 'Pending')
                            <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse block mt-1"></span>
                        @endif
                    </div>
                </div>
                
                <div class="space-y-2 mb-3">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user text-blue-500 w-4"></i>
                        <span class="text-sm text-gray-800">{{ $order['customer'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-phone text-green-500 w-4"></i>
                        <span class="text-sm text-gray-800">{{ $order['phone'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-utensils text-orange-500 w-4"></i>
                        <span class="text-sm text-gray-800">{{ $order['items'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-money-bill text-purple-500 w-4"></i>
                        <span class="text-sm font-semibold text-gray-800">PKR {{ $order['amount'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-credit-card text-gray-500 w-4"></i>
                        <span class="text-sm text-gray-800">{{ $order['payment_status'] }}</span>
                    </div>
                    @if(isset($order['special_instructions']))
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-orange-500 w-4"></i>
                        <span class="text-sm text-orange-600">Special: {{ $order['special_instructions'] }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-2">
                    <button class="px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition flex items-center justify-center gap-1">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="px-3 py-2 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition flex items-center justify-center gap-1">
                        <i class="fas fa-edit"></i> Update
                    </button>
                    <button class="px-3 py-2 bg-purple-500 text-white text-sm rounded-lg hover:bg-purple-600 transition flex items-center justify-center gap-1">
                        <i class="fas fa-phone"></i> Call
                    </button>
                    <button class="px-3 py-2 bg-orange-500 text-white text-sm rounded-lg hover:bg-orange-600 transition flex items-center justify-center gap-1">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-600">
                Showing 1 to 10 of 156 orders
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <button class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm">1</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">2</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">3</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Orders Chart
    const ctx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Orders',
                data: [12, 19, 15, 25, 22, 18, 23],
                borderColor: '#00d03c',
                backgroundColor: 'rgba(0, 208, 60, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'Revenue (PKR)',
                data: [450, 720, 580, 950, 820, 680, 920],
                borderColor: '#083344',
                backgroundColor: 'rgba(8, 51, 68, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Orders'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Revenue (PKR)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Bulk Actions
    const selectAllCheckbox = document.getElementById('selectAll');
    const orderCheckboxes = document.querySelectorAll('input[type="checkbox"]:not(#selectAll)');
    
    selectAllCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('input[type="checkbox"]:checked:not(#selectAll)').length;
        document.querySelector('span[class*="text-gray-500"]').textContent = `(${selectedCount} selected)`;
        
        if (selectedCount === orderCheckboxes.length) {
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.checked = false;
        }
    }

    // Real-time updates simulation
    setInterval(function() {
        const pendingOrders = document.querySelectorAll('.animate-pulse');
        pendingOrders.forEach(order => {
            // Simulate status updates
            if (Math.random() > 0.8) {
                order.classList.remove('animate-pulse');
                order.classList.add('bg-green-500');
            }
        });
    }, 5000);
});
</script>
@endsection
