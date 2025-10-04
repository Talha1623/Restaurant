@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <a href="{{ route('riders.index') }}" class="hover:text-green-600 transition-colors">
                    <i class="fas fa-arrow-left" style="color: #00d03c;"></i>
                </a>
                Rider Profile
            </h3>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ route('riders.edit', $rider) }}" 
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>

        <!-- Profile Header -->
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <!-- Profile Photo -->
            <div class="flex-shrink-0">
                @if($rider->photo)
                    <img src="{{ asset('storage/'.$rider->photo) }}" 
                         alt="Rider Photo" 
                         class="h-32 w-32 object-cover rounded-full shadow-lg border-4 border-white">
                @else
                    <div class="h-32 w-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                        <i class="fas fa-user text-white text-4xl"></i>
                    </div>
                @endif
            </div>

            <!-- Basic Info -->
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $rider->name }}</h2>
                <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm text-gray-600">
                    @if($rider->email)
                        <span class="flex items-center gap-1">
                            <i class="fas fa-envelope text-blue-500"></i>
                            {{ $rider->email }}
                        </span>
                    @endif
                    <span class="flex items-center gap-1">
                        <i class="fas fa-phone text-green-500"></i>
                        {{ $rider->phone }}
                    </span>
                    @if($rider->city)
                        <span class="flex items-center gap-1">
                            <i class="fas fa-map-marker-alt text-red-500"></i>
                            {{ $rider->city }}
                        </span>
                    @endif
                </div>
                
                <!-- Status Badge -->
                <div class="mt-3">
                    @if($rider->status=='active')
                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700 border border-green-200 flex items-center gap-1 w-fit mx-auto md:mx-0">
                            <i class="fas fa-check-circle"></i> Active
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm rounded-full bg-orange-100 text-orange-700 border border-orange-200 flex items-center gap-1 w-fit mx-auto md:mx-0">
                            <i class="fas fa-times-circle"></i> Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
                <i class="fas fa-user text-blue-600"></i>
                Personal Information
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">Full Name</span>
                    <span class="text-gray-800">{{ $rider->name }}</span>
                </div>
                @if($rider->dob)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">Date of Birth</span>
                    <span class="text-gray-800">{{ $rider->dob }}</span>
                </div>
                @endif
                @if($rider->ni_number)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">NI Number</span>
                    <span class="text-gray-800">{{ $rider->ni_number }}</span>
                </div>
                @endif
                @if($rider->cnic)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">CNIC</span>
                    <span class="text-gray-800">{{ $rider->cnic }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
                <i class="fas fa-address-book text-green-600"></i>
                Contact Information
            </h3>
            <div class="space-y-3">
                @if($rider->email)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">Email</span>
                    <span class="text-gray-800">{{ $rider->email }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">Phone</span>
                    <span class="text-gray-800">{{ $rider->phone }}</span>
                </div>
                @if($rider->address)
                <div class="flex justify-between items-start py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">Address</span>
                    <span class="text-gray-800 text-right">{{ $rider->address }}</span>
                </div>
                @endif
                @if($rider->city)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">City</span>
                    <span class="text-gray-800">{{ $rider->city }}</span>
                </div>
                @endif
                @if($rider->postcode)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">Postcode</span>
                    <span class="text-gray-800">{{ $rider->postcode }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
                <i class="fas fa-motorcycle text-purple-600"></i>
                Vehicle Information
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">Vehicle Type</span>
                    <span class="text-gray-800">{{ $rider->vehicle_type }}</span>
                </div>
                @if($rider->vehicle_number)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">Registration Number</span>
                    <span class="text-gray-800 font-mono">{{ $rider->vehicle_number }}</span>
                </div>
                @endif
                @if($rider->license_number)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">License Number</span>
                    <span class="text-gray-800">{{ $rider->license_number }}</span>
                </div>
                @endif
                @if($rider->license_expiry)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">License Expiry</span>
                    <span class="text-gray-800">{{ $rider->license_expiry }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Employment Information -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
                <i class="fas fa-briefcase text-orange-600"></i>
                Employment Information
            </h3>
            <div class="space-y-3">
                @if($rider->joining_date)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">Joining Date</span>
                    <span class="text-gray-800">{{ $rider->joining_date }}</span>
                </div>
                @endif
                @if($rider->bank_sort_code || $rider->bank_account_number)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium text-gray-600">Bank Details</span>
                    <span class="text-gray-800 font-mono">
                        @if($rider->bank_sort_code && $rider->bank_account_number)
                            {{ $rider->bank_sort_code }} - {{ $rider->bank_account_number }}
                        @elseif($rider->bank_sort_code)
                            {{ $rider->bank_sort_code }}
                        @elseif($rider->bank_account_number)
                            {{ $rider->bank_account_number }}
                        @endif
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    @if($rider->insurance_doc || $rider->mot_doc || $rider->right_to_work_doc || $rider->license_front_image || $rider->license_back_image)
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-file-alt text-indigo-600"></i>
            Documents
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($rider->license_front_image)
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-id-card text-green-500 text-2xl mb-2"></i>
                <p class="font-medium text-gray-700 mb-2">License Front</p>
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$rider->license_front_image) }}" 
                         alt="License Front" 
                         class="w-full h-32 object-cover rounded-lg border">
                </div>
                <a href="{{ asset('storage/'.$rider->license_front_image) }}" target="_blank" 
                   class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition">
                    <i class="fas fa-eye"></i> View Full
                </a>
            </div>
            @endif
            
            @if($rider->license_back_image)
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-id-card text-green-500 text-2xl mb-2"></i>
                <p class="font-medium text-gray-700 mb-2">License Back</p>
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$rider->license_back_image) }}" 
                         alt="License Back" 
                         class="w-full h-32 object-cover rounded-lg border">
                </div>
                <a href="{{ asset('storage/'.$rider->license_back_image) }}" target="_blank" 
                   class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition">
                    <i class="fas fa-eye"></i> View Full
                </a>
            </div>
            @endif
            @if($rider->insurance_doc)
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-shield-alt text-blue-500 text-2xl mb-2"></i>
                <p class="font-medium text-gray-700 mb-2">Insurance Document</p>
                <a href="{{ asset('storage/'.$rider->insurance_doc) }}" target="_blank" 
                   class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition">
                    <i class="fas fa-eye"></i> View
                </a>
            </div>
            @endif
            
            @if($rider->mot_doc)
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-car text-green-500 text-2xl mb-2"></i>
                <p class="font-medium text-gray-700 mb-2">MOT Certificate</p>
                <a href="{{ asset('storage/'.$rider->mot_doc) }}" target="_blank" 
                   class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition">
                    <i class="fas fa-eye"></i> View
                </a>
            </div>
            @endif
            
            @if($rider->right_to_work_doc)
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-passport text-purple-500 text-2xl mb-2"></i>
                <p class="font-medium text-gray-700 mb-2">Document License</p>
                <a href="{{ asset('storage/'.$rider->right_to_work_doc) }}" target="_blank" 
                   class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 rounded-md hover:bg-purple-200 transition">
                    <i class="fas fa-eye"></i> View
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Features/Notes Section -->
    @if($rider->features)
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-sticky-note text-yellow-600"></i>
            Features & Notes
        </h3>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-gray-700 leading-relaxed">{{ $rider->features }}</p>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex flex-wrap justify-end gap-3">
            <a href="{{ route('riders.services', $rider) }}" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-cogs"></i> Rider Services
            </a>
            <a href="{{ route('riders.analytics', $rider) }}" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-chart-bar"></i> Analytics
            </a>
            <a href="{{ route('riders.earnings', $rider) }}" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-money-bill-wave"></i> Earnings
            </a>
            <a href="{{ route('riders.assign-delivery', $rider) }}" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-truck"></i> Assign Delivery
            </a>
            <!-- <a href="#" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-comments"></i> Send Message
            </a> -->
            <!-- <a href="#" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-phone"></i> Call Rider
            </a> -->
            <!-- <a href="#" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-map-marker-alt"></i> Track Location
            </a> -->
            <!-- <a href="#" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-calendar-alt"></i> Schedule
            </a> -->
            <a href="{{ route('riders.payment-history', $rider) }}" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-file-invoice"></i> Payment History
            </a>
            <!-- <a href="#" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-tools"></i> Vehicle Maintenance
            </a> -->
            <!-- <a href="#" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-graduation-cap"></i> Training
            </a> -->
            <a href="{{ route('riders.performance', $rider) }}" 
               class="px-4 py-2 text-white rounded-lg transition flex items-center gap-2" style="background-color: #00d03c;">
                <i class="fas fa-star"></i> Performance Review
            </a>
        </div>

    </div>
</div>
@endsection
