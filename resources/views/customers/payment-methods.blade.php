@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('customers.show', $customer) }}" class="text-[#00d03c] hover:text-[#00b833] transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h3 class="text-2xl font-bold text-gray-800">Payment Methods</h3>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Customer:</span>
            <span class="font-semibold text-gray-700">{{ $customer->first_name }} {{ $customer->last_name }}</span>
        </div>
    </div>

    <!-- Payment Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Methods</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalMethods ?? 3 }}</p>
                </div>
                <i class="fas fa-credit-card text-blue-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Default Method</p>
                    <p class="text-2xl font-bold text-green-600">1</p>
                </div>
                <i class="fas fa-star text-green-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Transactions</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalTransactions ?? 45 }}</p>
                </div>
                <i class="fas fa-receipt text-purple-500 text-2xl"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Success Rate</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $successRate ?? 99 }}%</p>
                </div>
                <i class="fas fa-check-circle text-yellow-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Add New Payment Method Button -->
    <div class="mb-6">
        <button onclick="openAddPaymentModal()" class="px-4 py-2 bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors flex items-center gap-2">
            <i class="fas fa-plus"></i> Add New Payment Method
        </button>
    </div>

    <!-- Payment Methods List -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @for($i = 1; $i <= 3; $i++)
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-[#00d03c] rounded-full flex items-center justify-center">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ ['Visa Card', 'PayPal', 'Cash on Delivery'][$i-1] }}</h4>
                            <p class="text-sm text-gray-600">{{ $i == 1 ? 'Default Payment Method' : 'Secondary Payment Method' }}</p>
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
                    @if($i == 1)
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-credit-card text-gray-400"></i>
                        <span class="text-sm text-gray-800">**** **** **** 1234</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-calendar text-gray-400"></i>
                        <span class="text-sm text-gray-800">Expires 12/25</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user text-gray-400"></i>
                        <span class="text-sm text-gray-800">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                    </div>
                    @elseif($i == 2)
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-gray-400"></i>
                        <span class="text-sm text-gray-800">{{ $customer->email }}</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-shield-alt text-gray-400"></i>
                        <span class="text-sm text-gray-800">PayPal Secure</span>
                    </div>
                    @else
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-money-bill-wave text-gray-400"></i>
                        <span class="text-sm text-gray-800">Cash Payment</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-truck text-gray-400"></i>
                        <span class="text-sm text-gray-800">Pay on Delivery</span>
                    </div>
                    @endif
                    
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clock text-gray-400"></i>
                        <span class="text-sm text-gray-800">Added {{ date('M d, Y', strtotime('-' . $i . ' months')) }}</span>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <i class="fas fa-receipt"></i>
                            <span>{{ 15 + ($i * 5) }} transactions</span>
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

    <!-- Transaction History -->
    <div class="mt-6 bg-white rounded-lg shadow-md">
        <div class="p-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">Recent Transaction History</h4>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @for($i = 1; $i <= 8; $i++)
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('M d, Y', strtotime('-' . $i . ' days')) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-credit-card text-gray-400"></i>
                                <span>{{ ['Visa ****1234', 'PayPal', 'Cash on Delivery'][($i-1) % 3] }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">${{ 25.50 + ($i * 5) }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $i <= 5 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $i <= 5 ? 'Completed' : 'Pending' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-[#00d03c] hover:text-[#00b833] mr-3">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download"></i>
                            </button>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <!-- Security Information -->
    <div class="mt-6 bg-white rounded-lg shadow-md">
        <div class="p-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">Security & Privacy</h4>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <i class="fas fa-shield-alt text-green-500 text-2xl mb-2"></i>
                    <h5 class="font-medium text-gray-800 mb-1">PCI Compliant</h5>
                    <p class="text-sm text-gray-600">All payment data is encrypted and secure</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <i class="fas fa-lock text-blue-500 text-2xl mb-2"></i>
                    <h5 class="font-medium text-gray-800 mb-1">256-bit SSL</h5>
                    <p class="text-sm text-gray-600">Bank-level encryption for all transactions</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <i class="fas fa-user-shield text-purple-500 text-2xl mb-2"></i>
                    <h5 class="font-medium text-gray-800 mb-1">Fraud Protection</h5>
                    <p class="text-sm text-gray-600">Advanced fraud detection and prevention</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Method Modal -->
<div id="addPaymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Add New Payment Method</h3>
                    <button onclick="closeAddPaymentModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent">
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="cash">Cash on Delivery</option>
                        </select>
                    </div>
                    
                    <div id="cardFields">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent" placeholder="1234 5678 9012 3456">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent" placeholder="MM/YY">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent" placeholder="123">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cardholder Name</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent" placeholder="Full name on card">
                        </div>
                    </div>
                    
                    <div id="paypalFields" class="hidden">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PayPal Email</label>
                            <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00d03c] focus:border-transparent" placeholder="your@email.com">
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="setDefault" class="mr-2">
                        <label for="setDefault" class="text-sm text-gray-700">Set as default payment method</label>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeAddPaymentModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#00d03c] text-white rounded-lg hover:bg-[#00b833] transition-colors">
                            Add Payment Method
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
    
    .grid-cols-1.md\\:grid-cols-3 {
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
function openAddPaymentModal() {
    document.getElementById('addPaymentModal').classList.remove('hidden');
}

function closeAddPaymentModal() {
    document.getElementById('addPaymentModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('addPaymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddPaymentModal();
    }
});

// Handle payment type change
document.querySelector('#addPaymentModal select').addEventListener('change', function(e) {
    const cardFields = document.getElementById('cardFields');
    const paypalFields = document.getElementById('paypalFields');
    
    if (e.target.value === 'paypal') {
        cardFields.classList.add('hidden');
        paypalFields.classList.remove('hidden');
    } else if (e.target.value === 'cash') {
        cardFields.classList.add('hidden');
        paypalFields.classList.add('hidden');
    } else {
        cardFields.classList.remove('hidden');
        paypalFields.classList.add('hidden');
    }
});

// Handle form submission
document.querySelector('#addPaymentModal form').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add payment method logic here
    console.log('Adding new payment method...');
    closeAddPaymentModal();
});
</script>
@endsection
