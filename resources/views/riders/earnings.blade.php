@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('riders.show', $rider) }}" class="text-[#00d03c] hover:text-[#00b833] transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h3 class="text-2xl font-bold text-gray-800">Earnings</h3>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Rider:</span>
            <span class="font-semibold text-gray-700">{{ $rider->name }}</span>
        </div>
    </div>

    <!-- Earnings Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Earnings</p>
                    <p class="text-2xl font-bold text-green-600">${{ $totalEarnings ?? 45230 }}</p>
                </div>
                <i class="fas fa-dollar-sign text-green-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">This Month</p>
                    <p class="text-2xl font-bold text-blue-600">${{ $thisMonthEarnings ?? 8450 }}</p>
                </div>
                <i class="fas fa-calendar-alt text-blue-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">This Week</p>
                    <p class="text-2xl font-bold text-purple-600">${{ $thisWeekEarnings ?? 2150 }}</p>
                </div>
                <i class="fas fa-chart-line text-purple-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Today</p>
                    <p class="text-2xl font-bold text-yellow-600">${{ $todayEarnings ?? 185 }}</p>
                </div>
                <i class="fas fa-sun text-yellow-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Earnings Chart and Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Earnings Chart -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Earnings Trend</h4>
            </div>
            <div class="p-4">
                <canvas id="earningsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Performance Metrics</h4>
            </div>
            <div class="p-4 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Deliveries</span>
                    <span class="font-semibold text-gray-800">{{ $totalDeliveries ?? 1247 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Average per Delivery</span>
                    <span class="font-semibold text-gray-800">${{ $avgPerDelivery ?? 36.25 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Best Day</span>
                    <span class="font-semibold text-gray-800">${{ $bestDayEarnings ?? 285 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Customer Rating</span>
                    <span class="font-semibold text-gray-800">{{ $customerRating ?? 4.7 }}/5.0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">On-Time Rate</span>
                    <span class="font-semibold text-gray-800">{{ $onTimeRate ?? 96.2 }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Breakdown -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">Payment Breakdown</h4>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <i class="fas fa-money-bill-wave text-green-500 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600">Base Pay</p>
                    <p class="text-lg font-bold text-green-600">${{ $basePay ?? 1250 }}</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <i class="fas fa-truck text-blue-500 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600">Delivery Fees</p>
                    <p class="text-lg font-bold text-blue-600">${{ $deliveryFees ?? 3240 }}</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <i class="fas fa-gift text-purple-500 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600">Tips</p>
                    <p class="text-lg font-bold text-purple-600">${{ $tips ?? 890 }}</p>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <i class="fas fa-star text-yellow-500 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600">Bonuses</p>
                    <p class="text-lg font-bold text-yellow-600">${{ $bonuses ?? 450 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings History -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h4 class="text-lg font-semibold text-gray-800">Earnings History</h4>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors">
                        <i class="fas fa-download mr-1"></i>Export
                    </button>
                    <button class="px-3 py-1 text-sm bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-filter mr-1"></i>Filter
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Pay</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fees</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tips</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @for($i = 1; $i <= 10; $i++)
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('M d, Y', strtotime('-' . $i . ' days')) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ 8 + $i }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">${{ 45 + ($i * 2) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">${{ 120 + ($i * 5) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">${{ 25 + ($i * 3) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${{ 190 + ($i * 10) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $i <= 2 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $i <= 2 ? 'Paid' : 'Pending' }}
                            </span>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Earning Days & Insights -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Earning Days -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Top Earning Days</h4>
            </div>
            <div class="p-4 space-y-3">
                @for($i = 1; $i <= 5; $i++)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-[#00d03c] rounded-full flex items-center justify-center text-white text-sm font-bold">
                            {{ $i }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ date('M d, Y', strtotime('-' . ($i * 7) . ' days')) }}</p>
                            <p class="text-sm text-gray-600">{{ 12 + $i }} deliveries</p>
                        </div>
                    </div>
                    <span class="font-bold text-[#00d03c]">${{ 285 - ($i * 15) }}</span>
                </div>
                @endfor
            </div>
        </div>

        <!-- Peak Hours & Areas -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-800">Peak Performance</h4>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <h5 class="font-medium text-gray-800 mb-2">Peak Hours</h5>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">12:00 PM - 2:00 PM</span>
                            <span class="text-sm font-medium text-gray-800">${{ $peakHours1 ?? 45 }}/hour</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">6:00 PM - 9:00 PM</span>
                            <span class="text-sm font-medium text-gray-800">${{ $peakHours2 ?? 52 }}/hour</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">10:00 PM - 12:00 AM</span>
                            <span class="text-sm font-medium text-gray-800">${{ $peakHours3 ?? 38 }}/hour</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h5 class="font-medium text-gray-800 mb-2">Top Areas</h5>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Downtown</span>
                            <span class="text-sm font-medium text-gray-800">${{ $topArea1 ?? 1250 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Uptown</span>
                            <span class="text-sm font-medium text-gray-800">${{ $topArea2 ?? 980 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Suburbs</span>
                            <span class="text-sm font-medium text-gray-800">${{ $topArea3 ?? 750 }}</span>
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
    
    .grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
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
    // Earnings Chart
    const ctx = document.getElementById('earningsChart').getContext('2d');
    const earningsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Monthly Earnings',
                data: [3200, 3800, 4200, 3900, 4500, 4800, 5200, 5100, 4900, 5400, 5800, 6200],
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
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
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

    // Auto-refresh earnings data every 5 minutes
    setInterval(function() {
        // Add refresh logic here
        console.log('Refreshing earnings data...');
    }, 300000);
});
</script>
@endsection
