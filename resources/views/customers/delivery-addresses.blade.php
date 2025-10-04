@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('customers.show', $customer) }}" class="text-[#00d03c] hover:text-[#00b833] transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h3 class="text-2xl font-bold text-gray-800">Delivery Addresses</h3>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Customer:</span>
            <span class="font-semibold text-gray-700">{{ $customer->first_name }} {{ $customer->last_name }}</span>
        </div>
    </div>

    <!-- Address Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Addresses</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalAddresses ?? 3 }}</p>
                </div>
                <i class="fas fa-map-marker-alt text-blue-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Default Address</p>
                    <p class="text-2xl font-bold text-green-600">1</p>
                </div>
                <i class="fas fa-home text-green-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Recent Deliveries</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $recentDeliveries ?? 12 }}</p>
                </div>
                <i class="fas fa-truck text-purple-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Success Rate</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $successRate ?? 98 }}%</p>
                </div>
                <i class="fas fa-check-circle text-yellow-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Add New Address Button -->
    <div class="mb-6">
        <button onclick="openAddAddressModal()" class="px-4 py-2 bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors flex items-center gap-2">
            <i class="fas fa-plus"></i> Add New Address
        </button>
    </div>

    <!-- Addresses List -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @for($i = 1; $i <= 3; $i++)
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#00d03c] rounded-full flex items-center justify-center">
                            <i class="fas fa-home text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ ['Home', 'Work', 'Other'][$i-1] }} Address</h4>
                            <p class="text-sm text-gray-600">{{ $i == 1 ? 'Default Address' : 'Secondary Address' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($i == 1)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Default
                        </span>
                        @endif
                        <button class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-sm text-gray-800">123 Main Street, Apt {{ $i }}B</p>
                            <p class="text-sm text-gray-600">Downtown, City 12345</p>
                            <p class="text-sm text-gray-600">United Kingdom</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user text-gray-400"></i>
                        <span class="text-sm text-gray-800">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-gray-400"></i>
                        <span class="text-sm text-gray-800">{{ $customer->phone }}</span>
                    </div>
                    
                    @if($i == 1)
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-gray-400 mt-1"></i>
                        <p class="text-sm text-gray-600">Ring doorbell twice. Leave at front door if no answer.</p>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-truck"></i>
                            <span>{{ 5 + ($i * 2) }} recent deliveries</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            @if($i != 1)
                            <button class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                <i class="fas fa-star mr-1"></i>Set Default
                            </button>
                            @endif
                            <button class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>

    <!-- Delivery History -->
    <div class="mt-6 bg-white rounded-lg shadow-md">
        <div class="p-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">Recent Delivery History</h4>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Restaurant</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @for($i = 1; $i <= 5; $i++)
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('M d, Y', strtotime('-' . $i . ' days')) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>
                                <p class="font-medium">123 Main Street, Apt {{ $i }}B</p>
                                <p class="text-gray-600">Downtown, City 12345</p>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">Restaurant {{ $i }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $i <= 2 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $i <= 2 ? 'Delivered' : 'In Progress' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-[#00d03c] hover:text-[#00b833] mr-3">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-redo"></i>
                            </button>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div id="addAddressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Add New Address</h3>
                    <button onclick="closeAddAddressModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Type</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent">
                            <option value="home">Home</option>
                            <option value="work">Work</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent" placeholder="Street address">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent" placeholder="Apartment, suite, etc.">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent" placeholder="City">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Postcode</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent" placeholder="Postcode">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Instructions</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent" rows="3" placeholder="Special delivery instructions..."></textarea>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="setDefault" class="mr-2">
                        <label for="setDefault" class="text-sm text-gray-700">Set as default address</label>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeAddAddressModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors">
                            Add Address
                        </button>
                    </div>
                </form>
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
function openAddAddressModal() {
    document.getElementById('addAddressModal').classList.remove('hidden');
}

function closeAddAddressModal() {
    document.getElementById('addAddressModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('addAddressModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddAddressModal();
    }
});

// Handle form submission
document.querySelector('#addAddressModal form').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add address logic here
    console.log('Adding new address...');
    closeAddAddressModal();
});
</script>
@endsection
