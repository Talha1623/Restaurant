@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-4">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <a href="{{ route('customers.index') }}" class="hover:text-green-600 transition-colors">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                Customer Profile
            </h3>
            
            <!-- Action Buttons -->
            <div class="flex gap-1">
                <a href="{{ route('customers.edit', $customer) }}" 
                   class="px-2 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700 transition flex items-center gap-1">
                    <i class="fas fa-edit text-xs"></i> Edit
                </a>
            </div>
        </div>

        <!-- Profile Header -->
        <div class="flex flex-col md:flex-row items-center md:items-start gap-4">
            <!-- Profile Photo -->
            <div class="flex-shrink-0">
                <div class="h-20 w-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow border-2 border-white">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $customer->first_name }} {{ $customer->last_name }}</h2>
                <div class="flex flex-wrap justify-center md:justify-start gap-3 text-xs text-gray-600">
                    <span class="flex items-center gap-1">
                        <i class="fas fa-envelope text-blue-500"></i>
                        {{ $customer->email }}
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="fas fa-phone text-green-500"></i>
                        {{ $customer->phone ?? 'N/A' }}
                    </span>
                    @if($customer->city)
                        <span class="flex items-center gap-1">
                            <i class="fas fa-map-marker-alt text-red-500"></i>
                            {{ $customer->city }}
                        </span>
                    @endif
                </div>
                
                <!-- Status Badge -->
                <div class="mt-2">
                    @if($customer->status == 'active')
                        <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 flex items-center gap-1 w-fit mx-auto md:mx-0">
                            <i class="fas fa-check-circle"></i> Active
                        </span>
                    @elseif($customer->status == 'inactive')
                        <span class="px-2 py-0.5 text-xs rounded-full bg-orange-100 text-orange-700 flex items-center gap-1 w-fit mx-auto md:mx-0">
                            <i class="fas fa-times-circle"></i> Inactive
                        </span>
                    @elseif($customer->status == 'blocked')
                        <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700 flex items-center gap-1 w-fit mx-auto md:mx-0">
                            <i class="fas fa-ban"></i> Blocked
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        
        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <h3 class="text-md font-semibold text-gray-800 mb-3 flex items-center gap-2 border-b pb-1">
                <i class="fas fa-user text-blue-600"></i>
                Personal Information
            </h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">First Name</span>
                    <span class="text-gray-800 text-sm">{{ $customer->first_name }}</span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Last Name</span>
                    <span class="text-gray-800 text-sm">{{ $customer->last_name }}</span>
                </div>
                @if($customer->gender)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Gender</span>
                    <span class="text-gray-800 text-sm capitalize">{{ $customer->gender }}</span>
                </div>
                @endif
                @if($customer->dob)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Date of Birth</span>
                    <span class="text-gray-800 text-sm">{{ $customer->dob }}</span>
                </div>
                @endif
                @if($customer->ni_number)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">NI Number</span>
                    <span class="text-gray-800 text-sm">{{ $customer->ni_number }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <h3 class="text-md font-semibold text-gray-800 mb-3 flex items-center gap-2 border-b pb-1">
                <i class="fas fa-address-book text-green-600"></i>
                Contact Information
            </h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Email</span>
                    <span class="text-gray-800 text-sm">{{ $customer->email }}</span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Phone</span>
                    <span class="text-gray-800 text-sm">{{ $customer->phone ?? 'N/A' }}</span>
                </div>
                @if($customer->username)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Username</span>
                    <span class="text-gray-800 text-sm">{{ $customer->username }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <h3 class="text-md font-semibold text-gray-800 mb-3 flex items-center gap-2 border-b pb-1">
                <i class="fas fa-map-marker-alt text-red-600"></i>
                Address Information
            </h3>
            <div class="space-y-2">
                @if($customer->address_line1)
                <div class="flex justify-between items-start py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Address</span>
                    <span class="text-gray-800 text-right text-sm">{{ $customer->address_line1 }}</span>
                </div>
                @endif
                @if($customer->city)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">City</span>
                    <span class="text-gray-800 text-sm">{{ $customer->city }}</span>
                </div>
                @endif
                @if($customer->postcode)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Postcode</span>
                    <span class="text-gray-800 text-sm">{{ $customer->postcode }}</span>
                </div>
                @endif
                @if($customer->country)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Country</span>
                    <span class="text-gray-800 text-sm">{{ $customer->country }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <h3 class="text-md font-semibold text-gray-800 mb-3 flex items-center gap-2 border-b pb-1">
                <i class="fas fa-briefcase text-orange-600"></i>
                Account Information
            </h3>
            <div class="space-y-2">
                @if($customer->registration_date)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Registration Date</span>
                    <span class="text-gray-800 text-sm">{{ $customer->registration_date }}</span>
                </div>
                @endif
                @if($customer->preferred_payment_method)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Preferred Payment</span>
                    <span class="text-gray-800 text-sm">{{ $customer->preferred_payment_method }}</span>
                </div>
                @endif
                @if($customer->card_last_four)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Card Last 4</span>
                    <span class="text-gray-800 text-sm">{{ $customer->card_last_four }}</span>
                </div>
                @endif
                @if($customer->loyalty_points)
                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-600 text-sm">Loyalty Points</span>
                    <span class="text-gray-800 font-semibold text-sm">{{ $customer->loyalty_points }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>



    <!-- Preferences & Notes -->
    @if($customer->delivery_instructions || $customer->customer_type || $customer->notes)
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-sticky-note text-yellow-600"></i>
            Preferences & Notes
        </h3>
        <div class="space-y-3">
            @if($customer->delivery_instructions)
            <div class="flex justify-between items-start py-2 border-b border-gray-100">
                <span class="font-medium text-gray-600">Delivery Instructions</span>
                <span class="text-gray-800 text-right">{{ $customer->delivery_instructions }}</span>
            </div>
            @endif
            @if($customer->customer_type)
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="font-medium text-gray-600">Customer Type</span>
                <span class="text-gray-800">{{ $customer->customer_type }}</span>
            </div>
            @endif
            @if($customer->notes)
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700 leading-relaxed">{{ $customer->notes }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
        <div class="flex flex-wrap justify-end gap-2">
            <a href="{{ route('customers.services', $customer) }}" 
               class="px-2 py-1 text-white text-xs rounded transition flex items-center gap-1" style="background-color: #00d03c;">
                <i class="fas fa-cogs text-xs"></i> Services
            </a>
            <a href="{{ route('customers.order-history', $customer) }}" 
               class="px-2 py-1 text-white text-xs rounded transition flex items-center gap-1" style="background-color: #00d03c;">
                <i class="fas fa-shopping-cart text-xs"></i> Orders
            </a>
            <a href="{{ route('customers.payment-methods', $customer) }}" 
               class="px-2 py-1 text-white text-xs rounded transition flex items-center gap-1" style="background-color: #00d03c;">
                <i class="fas fa-credit-card text-xs"></i> Payments
            </a>
            <a href="{{ route('customers.delivery-addresses', $customer) }}" 
               class="px-2 py-1 text-white text-xs rounded transition flex items-center gap-1" style="background-color: #00d03c;">
                <i class="fas fa-map-marker-alt text-xs"></i> Addresses
            </a>
        </div>
    </div>

</div>
@endsection
