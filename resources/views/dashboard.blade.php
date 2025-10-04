@extends('layouts.app')

@section('content')
<div class="space-y-3 p-2">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-chart-line text-indigo-600"></i>
                Dashboard
            </h1>
            
            <!-- Search Bar -->
            <div class="relative w-48">
                <input type="text" placeholder="Search..." 
                       class="w-full pl-8 pr-3 py-1.5 text-sm rounded-md shadow-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search text-sm"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
        <!-- Total Restaurants -->
        <div class="bg-white rounded-lg shadow p-2 border border-gray-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Restaurants</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalRestaurants ?? 0 }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i> +12%
                    </p>
                </div>
                <div class="p-2 bg-gradient-to-br from-green-500 to-green-600 rounded-lg">
                    <i class="fas fa-store text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Total Riders -->
        <div class="bg-white rounded-lg shadow p-2 border border-gray-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Riders</p>
                    <p class="text-2xl font-bold text-gray-800">{{ ($activeRiders ?? 0) + ($inactiveRiders ?? 0) }}</p>
                    <p class="text-xs text-gray-600 mt-1">
                        <i class="fas fa-users"></i> All registered
                    </p>
                </div>
                <div class="p-2 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Active Riders -->
        <div class="bg-white rounded-lg shadow p-2 border border-gray-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Active Riders</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $activeRiders ?? 0 }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i> +8%
                    </p>
                </div>
                <div class="p-2 bg-gradient-to-br from-green-500 to-green-600 rounded-lg">
                    <i class="fas fa-motorcycle text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Inactive Riders -->
        <div class="bg-white rounded-lg shadow p-2 border border-gray-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Inactive Riders</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $inactiveRiders ?? 0 }}</p>
                    <p class="text-xs text-orange-600 mt-1">
                        <i class="fas fa-clock"></i> Inactive
                    </p>
                </div>
                <div class="p-2 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg">
                    <i class="fas fa-clock text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white rounded-lg shadow p-2 border border-gray-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalCustomers ?? 0 }}</p>
                    <p class="text-xs text-purple-600 mt-1">
                        <i class="fas fa-arrow-up"></i> +15%
                    </p>
                </div>
                <div class="p-2 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-lg shadow p-2 border border-gray-200 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-gray-800">£{{ number_format(rand(5000, 25000)) }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i> +22%
                    </p>
                </div>
                <div class="p-2 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg">
                    <i class="fas fa-pound-sign text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-3">
        <!-- Restaurant Trend Chart -->
        <div class="bg-white rounded-lg shadow p-2 border border-gray-200">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-green-600"></i>
                    Restaurant Growth
                </h3>
                <div class="flex gap-1">
                    <button class="px-1.5 py-0.5 text-xs bg-green-100 text-green-700 rounded">Weekly</button>
                    <button class="px-1.5 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">Monthly</button>
                </div>
            </div>
            <div class="h-32">
                <canvas id="restaurantChart"></canvas>
            </div>
        </div>

        <!-- Rider Trend Chart -->
        <div class="bg-white rounded-lg shadow p-2 border border-gray-200">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-line text-blue-600"></i>
                    Rider Registration
                </h3>
                <div class="flex gap-1">
                    <button class="px-1.5 py-0.5 text-xs bg-blue-100 text-blue-700 rounded">Weekly</button>
                    <button class="px-1.5 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">Monthly</button>
                </div>
            </div>
            <div class="h-32">
                <canvas id="riderChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <!-- Recently Added Restaurants -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-store text-green-600"></i>
                    Recent Restaurants
                </h3>
                <a href="{{ route('restaurants.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-2">
                @forelse($recentRestaurants ?? [] as $restaurant)
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-all">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-store text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 text-sm">{{ $restaurant->name }}</p>
                        <p class="text-xs text-gray-500">{{ $restaurant->city ?? 'N/A' }} • {{ $restaurant->cuisine_type ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">{{ $restaurant->created_at->diffForHumans() }}</p>
                        <span class="inline-block px-1.5 py-0.5 text-xs rounded-full {{ $restaurant->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($restaurant->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-store text-2xl mb-2 opacity-50"></i>
                    <p class="text-sm">No restaurants added yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recently Added Riders -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-motorcycle text-blue-600"></i>
                    Recent Riders
                </h3>
                <a href="{{ route('riders.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-2">
                @forelse($recentRiders ?? [] as $rider)
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-all">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-motorcycle text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 text-sm">{{ $rider->name }}</p>
                        <p class="text-xs text-gray-500">{{ $rider->city ?? 'N/A' }} • {{ $rider->vehicle_type ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">{{ $rider->created_at->diffForHumans() }}</p>
                        <span class="inline-block px-1.5 py-0.5 text-xs rounded-full {{ $rider->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($rider->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-motorcycle text-2xl mb-2 opacity-50"></i>
                    <p class="text-sm">No riders added yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
            <i class="fas fa-bolt text-yellow-600"></i>
            Quick Actions
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <a href="{{ route('restaurants.create') }}" class="p-3 border border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all group">
                <div class="text-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-white text-sm"></i>
                    </div>
                    <p class="font-medium text-gray-800 text-sm">Add Restaurant</p>
                    <p class="text-xs text-gray-500">Create new restaurant</p>
                </div>
            </a>
            
            <a href="{{ route('riders.create') }}" class="p-3 border border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all group">
                <div class="text-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-white text-sm"></i>
                    </div>
                    <p class="font-medium text-gray-800 text-sm">Add Rider</p>
                    <p class="text-xs text-gray-500">Register new rider</p>
                </div>
            </a>
            
            <a href="{{ route('customers.create') }}" class="p-3 border border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all group">
                <div class="text-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-white text-sm"></i>
                    </div>
                    <p class="font-medium text-gray-800 text-sm">Add Customer</p>
                    <p class="text-xs text-gray-500">Create new customer</p>
                </div>
            </a>
            
            <a href="#" class="p-3 border border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-all group">
                <div class="text-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-bar text-white text-sm"></i>
                    </div>
                    <p class="font-medium text-gray-800 text-sm">View Reports</p>
                    <p class="text-xs text-gray-500">Analytics & insights</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load Chart.js asynchronously for better performance -->
<script>
// Load Chart.js asynchronously
function loadChartJS() {
    return new Promise((resolve) => {
        if (typeof Chart !== 'undefined') {
            resolve();
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        script.onload = resolve;
        script.onerror = resolve; // Continue even if Chart.js fails to load
        document.head.appendChild(script);
    });
}

// Initialize charts after page is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Delay chart initialization to improve page load performance
    setTimeout(() => {
        loadChartJS().then(() => {
            initializeCharts();
        });
    }, 1000);
});

function initializeCharts() {
    // Get chart elements
    const restaurantCtx = document.getElementById('restaurantChart');
    const riderCtx = document.getElementById('riderChart');
    
    // Sample data for charts
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    const restaurantData = [12, 19, 15, 25, 22, 30];
    const riderData = [8, 12, 15, 18, 22, 28];
    
    // Restaurant Chart
    if (restaurantCtx && typeof Chart !== 'undefined') {
        new Chart(restaurantCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'New Restaurants',
                    data: restaurantData,
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    // Rider Chart
    if (riderCtx && typeof Chart !== 'undefined') {
        new Chart(riderCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'New Riders',
                    data: riderData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
}
</script>
@endpush
    