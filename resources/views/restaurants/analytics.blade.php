@extends('layouts.app')

@section('title', $restaurant->name . ' - Analytics')
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
                    {{ $restaurant->name }} - Analytics
                </h3>
                <p class="text-gray-600 text-sm mt-1">Performance insights and business metrics</p>
            </div>
            
            <!-- Time Period Selector -->
            <div class="flex gap-2">
                <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg">7 Days</button>
                <button class="px-3 py-2 border border-gray-300 text-sm rounded-lg hover:bg-gray-50">30 Days</button>
                <button class="px-3 py-2 border border-gray-300 text-sm rounded-lg hover:bg-gray-50">3 Months</button>
                <button class="px-3 py-2 border border-gray-300 text-sm rounded-lg hover:bg-gray-50">1 Year</button>
            </div>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800">PKR 12,450</p>
                    <p class="text-xs text-green-600">+15% from last week</p>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-pound-sign text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Order Volume -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Order Volume</p>
                    <p class="text-2xl font-bold text-gray-800">156</p>
                    <p class="text-xs text-green-600">+8% from last week</p>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-shopping-bag text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Average Order Value -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Avg Order Value</p>
                    <p class="text-2xl font-bold text-gray-800">PKR 79.8</p>
                    <p class="text-xs text-green-600">+5% from last week</p>
                </div>
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-calculator text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Customer Growth -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">New Customers</p>
                    <p class="text-2xl font-bold text-gray-800">23</p>
                    <p class="text-xs text-green-600">+12% from last week</p>
                </div>
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-user-plus text-orange-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue & Order Trends -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Revenue Trends</h3>
                <div class="flex gap-2">
                    <button class="px-2 py-1 bg-green-600 text-white text-xs rounded">Daily</button>
                    <button class="px-2 py-1 border border-gray-300 text-xs rounded hover:bg-gray-50">Weekly</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Order Volume Chart -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Order Volume</h3>
                <div class="flex gap-2">
                    <button class="px-2 py-1 bg-green-600 text-white text-xs rounded">Daily</button>
                    <button class="px-2 py-1 border border-gray-300 text-xs rounded hover:bg-gray-50">Weekly</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="orderVolumeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Peak Hours & Customer Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Peak Hours -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Peak Hours Analysis</h3>
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
                        <p class="font-semibold text-gray-800">45 orders</p>
                        <p class="text-sm text-blue-600">PKR 3,580</p>
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
                        <p class="font-semibold text-gray-800">38 orders</p>
                        <p class="text-sm text-orange-600">PKR 3,020</p>
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
                        <p class="font-semibold text-gray-800">12 orders</p>
                        <p class="text-sm text-gray-600">PKR 950</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Segments -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Segments</h3>
            <div class="h-64">
                <canvas id="customerChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Menu Performance -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Top Performing Menu Items</h3>
            <div class="flex gap-2">
                <button class="px-2 py-1 bg-green-600 text-white text-xs rounded">Revenue</button>
                <button class="px-2 py-1 border border-gray-300 text-xs rounded hover:bg-gray-50">Orders</button>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Top Items by Revenue -->
            <div>
                <h4 class="font-medium text-gray-700 mb-3">By Revenue</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hamburger text-orange-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Fish & Chips</p>
                                <p class="text-sm text-gray-600">PKR 12.99</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">PKR 1,558</p>
                            <p class="text-sm text-green-600">120 orders</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-pepper-hot text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Chicken Tikka Masala</p>
                                <p class="text-sm text-gray-600">PKR 14.99</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">PKR 1,199</p>
                            <p class="text-sm text-green-600">80 orders</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-drumstick-bite text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Sunday Roast Beef</p>
                                <p class="text-sm text-gray-600">PKR 18.50</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">PKR 1,110</p>
                            <p class="text-sm text-green-600">60 orders</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Items by Orders -->
            <div>
                <h4 class="font-medium text-gray-700 mb-3">By Order Count</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hamburger text-orange-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Fish & Chips</p>
                                <p class="text-sm text-gray-600">PKR 12.99</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">120 orders</p>
                            <p class="text-sm text-blue-600">PKR 1,558</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-pepper-hot text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Chicken Tikka Masala</p>
                                <p class="text-sm text-gray-600">PKR 14.99</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">80 orders</p>
                            <p class="text-sm text-blue-600">PKR 1,199</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-ice-cream text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Sticky Toffee Pudding</p>
                                <p class="text-sm text-gray-600">PKR 6.99</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">75 orders</p>
                            <p class="text-sm text-blue-600">PKR 524</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Day of Week Performance -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Day of Week Performance</h3>
        <div class="h-64">
            <canvas id="dayOfWeekChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Revenue (PKR)',
                data: [1200, 1900, 1500, 2500, 2200, 1800, 2300],
                borderColor: '#00d03c',
                backgroundColor: 'rgba(0, 208, 60, 0.1)',
                borderWidth: 3,
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
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue (PKR)'
                    }
                }
            }
        }
    });

    // Order Volume Chart
    const orderCtx = document.getElementById('orderVolumeChart').getContext('2d');
    new Chart(orderCtx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Orders',
                data: [15, 24, 19, 32, 28, 23, 30],
                backgroundColor: '#083344',
                borderColor: '#083344',
                borderWidth: 1
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
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Orders'
                    }
                }
            }
        }
    });

    // Customer Segments Chart
    const customerCtx = document.getElementById('customerChart').getContext('2d');
    new Chart(customerCtx, {
        type: 'doughnut',
        data: {
            labels: ['New Customers', 'Returning Customers', 'VIP Customers'],
            datasets: [{
                data: [35, 50, 15],
                backgroundColor: ['#00d03c', '#083344', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Day of Week Chart
    const dayCtx = document.getElementById('dayOfWeekChart').getContext('2d');
    new Chart(dayCtx, {
        type: 'bar',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [{
                label: 'Revenue (PKR)',
                data: [1200, 1900, 1500, 2500, 2200, 1800, 2300],
                backgroundColor: 'rgba(0, 208, 60, 0.8)',
                borderColor: '#00d03c',
                borderWidth: 1
            }, {
                label: 'Orders',
                data: [15, 24, 19, 32, 28, 23, 30],
                backgroundColor: 'rgba(8, 51, 68, 0.8)',
                borderColor: '#083344',
                borderWidth: 1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue (PKR)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Orders'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
});
</script>
@endsection
