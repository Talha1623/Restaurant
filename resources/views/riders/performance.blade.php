@extends('layouts.app')

@section('title', $rider->name . ' - Performance Review')
@section('content')
<div class="max-w-7xl mx-auto space-y-4 p-3">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <a href="{{ route('riders.show', $rider) }}" class="hover:text-green-600 transition-colors">
                        <i class="fas fa-arrow-left" style="color: #00d03c;"></i>
                    </a>
                    {{ $rider->name }} - Performance Review
                </h3>
                <p class="text-gray-600 text-sm mt-1">Comprehensive performance analysis and review</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-2">
                <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-1">
                    <i class="fas fa-plus"></i> New Review
                </button>
                <button class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
                    <i class="fas fa-download"></i> Export Report
                </button>
            </div>
        </div>
    </div>

    <!-- Performance Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Overall Rating -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Overall Rating</p>
                    <p class="text-2xl font-bold text-gray-800">4.7</p>
                    <div class="flex items-center gap-1 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= 4)
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                            @elseif($i == 5)
                                <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                            @endif
                        @endfor
                        <span class="text-xs text-gray-600 ml-1">(4.7/5)</span>
                    </div>
                </div>
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-star text-yellow-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Total Deliveries -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Deliveries</p>
                    <p class="text-2xl font-bold text-gray-800">1,247</p>
                    <p class="text-xs text-green-600">+12% this month</p>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-truck text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Success Rate</p>
                    <p class="text-2xl font-bold text-gray-800">98.5%</p>
                    <p class="text-xs text-green-600">+2.1% improvement</p>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Earnings</p>
                    <p class="text-2xl font-bold text-gray-800">PKR 18,450</p>
                    <p class="text-xs text-green-600">+8% this month</p>
                </div>
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-pound-sign text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Monthly Performance -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Monthly Performance</h3>
                <div class="flex gap-2">
                    <button class="px-2 py-1 bg-green-600 text-white text-xs rounded">6 Months</button>
                    <button class="px-2 py-1 border border-gray-300 text-xs rounded hover:bg-gray-50">1 Year</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="monthlyPerformanceChart"></canvas>
            </div>
        </div>

        <!-- Rating Trends -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Rating Trends</h3>
                <div class="flex gap-2">
                    <button class="px-2 py-1 bg-green-600 text-white text-xs rounded">3 Months</button>
                    <button class="px-2 py-1 border border-gray-300 text-xs rounded hover:bg-gray-50">6 Months</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="ratingTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Performance Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Key Metrics -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Key Performance Metrics</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">On-Time Delivery</p>
                            <p class="text-sm text-gray-600">Average delivery time</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">96.2%</p>
                        <p class="text-sm text-green-600">+1.8% this month</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-smile text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Customer Satisfaction</p>
                            <p class="text-sm text-gray-600">Based on reviews</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">4.7/5</p>
                        <p class="text-sm text-green-600">+0.2 improvement</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Distance Covered</p>
                            <p class="text-sm text-gray-600">Total km this month</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">1,247 km</p>
                        <p class="text-sm text-green-600">+156 km this month</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-award text-orange-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Performance Score</p>
                            <p class="text-sm text-gray-600">Overall rating</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">94/100</p>
                        <p class="text-sm text-green-600">+3 points this month</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Goals & Targets -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Goals & Targets</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">Delivery Target</p>
                        <p class="text-sm text-gray-600">200 deliveries/month</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">247/200</p>
                        <p class="text-sm text-green-600">✓ Exceeded</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">Rating Target</p>
                        <p class="text-sm text-gray-600">4.5+ average rating</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">4.7/4.5</p>
                        <p class="text-sm text-green-600">✓ Exceeded</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">On-Time Target</p>
                        <p class="text-sm text-gray-600">95% on-time delivery</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">96.2/95%</p>
                        <p class="text-sm text-green-600">✓ Exceeded</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">Earnings Target</p>
                        <p class="text-sm text-gray-600">PKR 15,000/month</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">PKR 18,450/15,000</p>
                        <p class="text-sm text-green-600">✓ Exceeded</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Recent Customer Reviews</h3>
            <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                View All Reviews
            </button>
        </div>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Sarah Johnson</p>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                @endfor
                                <span class="text-xs text-gray-600 ml-1">5.0</span>
                            </div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">2 days ago</span>
                </div>
                <p class="text-sm text-gray-700">Excellent service! Very professional and delivered on time. Highly recommend this rider.</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Mike Wilson</p>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 4; $i++)
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                @endfor
                                <i class="far fa-star text-gray-300 text-xs"></i>
                                <span class="text-xs text-gray-600 ml-1">4.0</span>
                            </div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">1 week ago</span>
                </div>
                <p class="text-sm text-gray-700">Good service overall. Food arrived warm and on time. Would use again.</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Emma Brown</p>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                @endfor
                                <span class="text-xs text-gray-600 ml-1">5.0</span>
                            </div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">2 weeks ago</span>
                </div>
                <p class="text-sm text-gray-700">Outstanding delivery service! Very friendly and professional. Food was perfect temperature.</p>
            </div>
        </div>
    </div>

    <!-- Performance History -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance Review History</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">December 2024 Review</p>
                        <p class="text-sm text-gray-600">Overall Score: 94/100</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Dec 15, 2024</p>
                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">View</button>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">November 2024 Review</p>
                        <p class="text-sm text-gray-600">Overall Score: 91/100</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Nov 15, 2024</p>
                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">View</button>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-orange-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">October 2024 Review</p>
                        <p class="text-sm text-gray-600">Overall Score: 88/100</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Oct 15, 2024</p>
                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">View</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Performance Chart
    const monthlyCtx = document.getElementById('monthlyPerformanceChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Deliveries',
                data: [180, 195, 210, 220, 235, 247],
                borderColor: '#00d03c',
                backgroundColor: 'rgba(0, 208, 60, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Rating',
                data: [4.2, 4.3, 4.4, 4.5, 4.6, 4.7],
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
                        text: 'Deliveries'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Rating'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Rating Trends Chart
    const ratingCtx = document.getElementById('ratingTrendsChart').getContext('2d');
    new Chart(ratingCtx, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Average Rating',
                data: [4.6, 4.7, 4.8, 4.7],
                backgroundColor: '#00d03c',
                borderColor: '#00d03c',
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
                    beginAtZero: false,
                    min: 4.0,
                    max: 5.0,
                    title: {
                        display: true,
                        text: 'Rating'
                    }
                }
            }
        }
    });
});
</script>
@endsection
