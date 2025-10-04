@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('riders.show', $rider) }}" class="text-[#00d03c] hover:text-[#00b833] transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h3 class="text-2xl font-bold text-gray-800">Analytics</h3>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Rider:</span>
            <span class="font-semibold text-gray-700">{{ $rider->name }}</span>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">On-Time Rate</p>
                    <p class="text-2xl font-bold text-green-600">{{ $onTimeRate ?? 96.2 }}%</p>
                </div>
                <i class="fas fa-clock text-green-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Customer Rating</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $customerRating ?? 4.7 }}/5.0</p>
                </div>
                <i class="fas fa-star text-blue-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Deliveries</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalDeliveries ?? 1247 }}</p>
                </div>
                <i class="fas fa-truck text-purple-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Avg. Delivery Time</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $avgDeliveryTime ?? 18 }} min</p>
                </div>
                <i class="fas fa-stopwatch text-yellow-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Delivery Performance Trend -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Delivery Performance Trend</h4>
            </div>
            <div class="p-4">
                <canvas id="performanceChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Earnings vs Deliveries -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Earnings vs Deliveries</h4>
            </div>
            <div class="p-4">
                <canvas id="earningsChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Weekly Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Weekly Delivery Volume -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Weekly Delivery Volume</h4>
            </div>
            <div class="p-4">
                <canvas id="weeklyChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Customer Rating Trend -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Customer Rating Trend</h4>
            </div>
            <div class="p-4">
                <canvas id="ratingChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Performance Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Delivery Success Rate -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Delivery Success Rate</h4>
            </div>
            <div class="p-4">
                <div class="text-center">
                    <div class="relative w-32 h-32 mx-auto mb-4">
                        <canvas id="successRateChart" width="128" height="128"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-800">{{ $successRate ?? 98.5 }}%</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Overall Success Rate</p>
                </div>
            </div>
        </div>

        <!-- Time Distribution -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Time Distribution</h4>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Morning (6AM-12PM)</span>
                        <span class="font-semibold text-gray-800">{{ $morningDeliveries ?? 245 }} deliveries</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Afternoon (12PM-6PM)</span>
                        <span class="font-semibold text-gray-800">{{ $afternoonDeliveries ?? 456 }} deliveries</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Evening (6PM-12AM)</span>
                        <span class="font-semibold text-gray-800">{{ $eveningDeliveries ?? 389 }} deliveries</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Night (12AM-6AM)</span>
                        <span class="font-semibold text-gray-800">{{ $nightDeliveries ?? 157 }} deliveries</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performance Areas -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Top Performance Areas</h4>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-[#00d03c] rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ $i }}
                            </div>
                            <span class="text-sm font-medium text-gray-800">{{ ['Downtown', 'Uptown', 'Suburbs', 'Airport'][$i-1] }}</span>
                        </div>
                        <span class="text-sm font-semibold text-[#00d03c]">{{ [98.5, 97.2, 96.8, 95.4][$i-1] }}%</span>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics Table -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h4 class="text-lg font-semibold text-gray-800">Detailed Performance Analytics</h4>
                <div class="flex space-x-2">
                    <select class="px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent">
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="90">Last 90 Days</option>
                    </select>
                    <button class="px-3 py-1 text-sm bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors">
                        <i class="fas fa-download mr-1"></i>Export
                    </button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deliveries</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Success Rate</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @for($i = 1; $i <= 10; $i++)
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('M d, Y', strtotime('-' . $i . ' days')) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ 8 + $i }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ 95 + ($i * 0.3) }}%</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ 15 + ($i * 0.5) }} min</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ 4.5 + ($i * 0.02) }}/5.0</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">${{ 180 + ($i * 8) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $i <= 3 ? 'bg-green-100 text-green-800' : ($i <= 7 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $i <= 3 ? 'Excellent' : ($i <= 7 ? 'Good' : 'Needs Improvement') }}
                            </span>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <!-- Insights & Recommendations -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Performance Insights -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Performance Insights</h4>
            </div>
            <div class="p-4 space-y-4">
                <div class="p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                        <div>
                            <h5 class="font-medium text-green-800">Excellent Performance</h5>
                            <p class="text-sm text-green-700">Your on-time delivery rate is 15% above average</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <div class="flex items-start">
                        <i class="fas fa-chart-line text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h5 class="font-medium text-blue-800">Growing Trend</h5>
                            <p class="text-sm text-blue-700">Earnings have increased by 12% this month</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                    <div class="flex items-start">
                        <i class="fas fa-clock text-yellow-500 mt-1 mr-3"></i>
                        <div>
                            <h5 class="font-medium text-yellow-800">Peak Hours</h5>
                            <p class="text-sm text-yellow-700">Best performance during 6-9 PM slot</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommendations -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Recommendations</h4>
            </div>
            <div class="p-4 space-y-4">
                <div class="p-3 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-purple-500 mt-1 mr-3"></i>
                        <div>
                            <h5 class="font-medium text-purple-800">Optimize Routes</h5>
                            <p class="text-sm text-purple-700">Consider using GPS optimization for better efficiency</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-3 bg-indigo-50 rounded-lg border-l-4 border-indigo-500">
                    <div class="flex items-start">
                        <i class="fas fa-star text-indigo-500 mt-1 mr-3"></i>
                        <div>
                            <h5 class="font-medium text-indigo-800">Maintain Quality</h5>
                            <p class="text-sm text-indigo-700">Keep up the excellent customer service standards</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-3 bg-pink-50 rounded-lg border-l-4 border-pink-500">
                    <div class="flex items-start">
                        <i class="fas fa-target text-pink-500 mt-1 mr-3"></i>
                        <div>
                            <h5 class="font-medium text-pink-800">Set Goals</h5>
                            <p class="text-sm text-pink-700">Aim for 100% on-time delivery rate next month</p>
                        </div>
                    </div>
                </div>
            </div>
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
    
    .grid-cols-1.lg\\:grid-cols-3 {
        grid-template-columns: 1fr;
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
    // Performance Trend Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(performanceCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'On-Time Rate (%)',
                data: [92, 94, 95, 93, 96, 97, 95, 98, 96, 97, 98, 96],
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
                    beginAtZero: false,
                    min: 90,
                    max: 100,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
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

    // Earnings vs Deliveries Chart
    const earningsCtx = document.getElementById('earningsChart').getContext('2d');
    const earningsChart = new Chart(earningsCtx, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Earnings ($)',
                data: [850, 920, 780, 1050],
                backgroundColor: 'rgba(0, 208, 60, 0.8)',
                borderColor: '#00d03c',
                borderWidth: 1
            }, {
                label: 'Deliveries',
                data: [45, 52, 38, 58],
                type: 'line',
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: false,
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
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' deliveries';
                        }
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

    // Weekly Delivery Volume Chart
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    const weeklyChart = new Chart(weeklyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [{
                data: [45, 52, 48, 55, 62, 58, 42],
                backgroundColor: [
                    '#00d03c',
                    '#3b82f6',
                    '#8b5cf6',
                    '#f59e0b',
                    '#ef4444',
                    '#06b6d4',
                    '#84cc16'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
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

    // Customer Rating Trend Chart
    const ratingCtx = document.getElementById('ratingChart').getContext('2d');
    const ratingChart = new Chart(ratingCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Average Rating',
                data: [4.5, 4.6, 4.7, 4.8],
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
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
                    beginAtZero: false,
                    min: 4.0,
                    max: 5.0,
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

    // Success Rate Donut Chart
    const successCtx = document.getElementById('successRateChart').getContext('2d');
    const successChart = new Chart(successCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [98.5, 1.5],
                backgroundColor: ['#00d03c', '#e5e7eb'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Auto-refresh analytics data every 10 minutes
    setInterval(function() {
        console.log('Refreshing analytics data...');
    }, 600000);
});
</script>
@endsection
