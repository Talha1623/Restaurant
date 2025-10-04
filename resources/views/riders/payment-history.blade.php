@extends('layouts.app')

@section('title', $rider->name . ' - Payment History')
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
                    {{ $rider->name }} - Payment History
                </h3>
                <p class="text-gray-600 text-sm mt-1">Complete payment history and earnings overview</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-2">
                <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-1">
                    <i class="fas fa-download"></i> Export Report
                </button>
                <button class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
                    <i class="fas fa-file-invoice"></i> Generate Invoice
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Total Earnings -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Earnings</p>
                    <p class="text-2xl font-bold text-gray-800">PKR 45,230</p>
                    <p class="text-xs text-green-600">+15% this year</p>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-pound-sign text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">This Month</p>
                    <p class="text-2xl font-bold text-gray-800">PKR 8,450</p>
                    <p class="text-xs text-green-600">+8% vs last month</p>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar-alt text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Last Payment -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Last Payment</p>
                    <p class="text-2xl font-bold text-gray-800">PKR 2,150</p>
                    <p class="text-xs text-gray-600">Dec 15, 2024</p>
                </div>
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-check-circle text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Pending Amount -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Pending Amount</p>
                    <p class="text-2xl font-bold text-gray-800">PKR 1,850</p>
                    <p class="text-xs text-orange-600">Next payment: Jan 1</p>
                </div>
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-clock text-orange-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Monthly Earnings Chart -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Monthly Earnings</h3>
                <div class="flex gap-2">
                    <button class="px-2 py-1 bg-green-600 text-white text-xs rounded">6 Months</button>
                    <button class="px-2 py-1 border border-gray-300 text-xs rounded hover:bg-gray-50">1 Year</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="monthlyEarningsChart"></canvas>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Methods</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-university text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Bank Transfer</p>
                            <p class="text-sm text-gray-600">HSBC - ****1234</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">85%</p>
                        <p class="text-sm text-gray-600">Primary</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Mobile Payment</p>
                            <p class="text-sm text-gray-600">PayPal - ****5678</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">15%</p>
                        <p class="text-sm text-gray-600">Secondary</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Payment History</h3>
            <div class="flex gap-2">
                <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option>All Payments</option>
                    <option>This Month</option>
                    <option>Last 3 Months</option>
                    <option>This Year</option>
                </select>
                <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                    Filter
                </button>
            </div>
        </div>
        
        <!-- Desktop View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Payment ID</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Date</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Amount</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Method</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-800">PAY-001</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600">Dec 15, 2024</td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-gray-800">PKR 2,150</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-university text-blue-600"></i>
                                <span class="text-gray-600">Bank Transfer</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">View</button>
                                <button class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">Download</button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-800">PAY-002</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600">Nov 15, 2024</td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-gray-800">PKR 1,980</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-university text-blue-600"></i>
                                <span class="text-gray-600">Bank Transfer</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">View</button>
                                <button class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">Download</button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-800">PAY-003</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600">Oct 15, 2024</td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-gray-800">PKR 2,320</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-mobile-alt text-green-600"></i>
                                <span class="text-gray-600">Mobile Payment</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">View</button>
                                <button class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">Download</button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-800">PAY-004</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600">Sep 15, 2024</td>
                        <td class="py-3 px-4">
                            <span class="font-semibold text-gray-800">PKR 1,750</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-university text-blue-600"></i>
                                <span class="text-gray-600">Bank Transfer</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">View</button>
                                <button class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">Download</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile View -->
        <div class="lg:hidden space-y-3">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-800">PAY-001</span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                    </div>
                    <span class="text-sm text-gray-500">Dec 15, 2024</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-university text-blue-600"></i>
                        <span class="text-gray-600">Bank Transfer</span>
                    </div>
                    <span class="font-semibold text-gray-800">PKR 2,150</span>
                </div>
                <div class="flex gap-2 mt-3">
                    <button class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">View</button>
                    <button class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">Download</button>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-800">PAY-002</span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                    </div>
                    <span class="text-sm text-gray-500">Nov 15, 2024</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-university text-blue-600"></i>
                        <span class="text-gray-600">Bank Transfer</span>
                    </div>
                    <span class="font-semibold text-gray-800">PKR 1,980</span>
                </div>
                <div class="flex gap-2 mt-3">
                    <button class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">View</button>
                    <button class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">Download</button>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-800">PAY-003</span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                    </div>
                    <span class="text-sm text-gray-500">Oct 15, 2024</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-mobile-alt text-green-600"></i>
                        <span class="text-gray-600">Mobile Payment</span>
                    </div>
                    <span class="font-semibold text-gray-800">PKR 2,320</span>
                </div>
                <div class="flex gap-2 mt-3">
                    <button class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">View</button>
                    <button class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">Download</button>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4">
            <p class="text-sm text-gray-600">Showing 1-4 of 12 payments</p>
            <div class="flex gap-2">
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Previous</button>
                <button class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm">1</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">2</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">3</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Next</button>
            </div>
        </div>
    </div>

    <!-- Bank Details & Tax Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Bank Details -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Bank Details</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Bank Name:</span>
                    <span class="font-medium text-gray-800">HSBC Bank</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Account Number:</span>
                    <span class="font-medium text-gray-800">****1234</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Sort Code:</span>
                    <span class="font-medium text-gray-800">40-02-26</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Account Holder:</span>
                    <span class="font-medium text-gray-800">{{ $rider->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Payment Schedule:</span>
                    <span class="font-medium text-gray-800">Monthly (15th)</span>
                </div>
            </div>
            <button class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Update Bank Details
            </button>
        </div>

        <!-- Tax Information -->
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tax Information</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Tax ID:</span>
                    <span class="font-medium text-gray-800">GB123456789</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Tax Rate:</span>
                    <span class="font-medium text-gray-800">20%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Total Tax Paid:</span>
                    <span class="font-medium text-gray-800">PKR 9,046</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">This Year Tax:</span>
                    <span class="font-medium text-gray-800">PKR 1,690</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Tax Status:</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Up to Date</span>
                </div>
            </div>
            <button class="w-full mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Download Tax Certificate
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Earnings Chart
    const earningsCtx = document.getElementById('monthlyEarningsChart').getContext('2d');
    new Chart(earningsCtx, {
        type: 'bar',
        data: {
            labels: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Earnings (PKR)',
                data: [1750, 1980, 2320, 2150, 1980, 2150],
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
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Earnings (PKR)'
                    }
                }
            }
        }
    });
});
</script>
@endsection
