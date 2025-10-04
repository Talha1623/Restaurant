@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 shadow-lg rounded-xl border border-gray-200">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 border-b pb-3">
        <h2 class="text-2xl font-bold flex items-center gap-2 text-gray-800">
            <a href="{{ route('restaurants.show', $restaurantId) }}" class="hover:text-green-600 transition-colors">
                <i class="fas fa-arrow-left text-green-600 hover:text-green-700"></i>
            </a> Add New Certificate
        </h2>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <h4 class="font-bold">Please fix the following errors:</h4>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('certificates.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="restaurant_id" value="{{ $restaurantId }}">

        <div class="space-y-6">
            <!-- Certificate Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Certificate Name *</label>
                    <input type="text" name="name" class="w-full border px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Certificate Type *</label>
                    <select name="type" class="w-full border px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400" required>
                        <option value="">Select Certificate Type</option>
                        @foreach($certificateTypes as $certType)
                            <option value="{{ $certType->name }}">{{ $certType->name }}</option>
                        @endforeach
                    </select>
                    @if($certificateTypes->isEmpty())
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle"></i> 
                            No certificate types available. 
                            <a href="{{ route('settings.index') }}" class="text-blue-600 hover:underline">Add types in Settings</a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Issue Date *</label>
                    <input type="date" name="issue_date" class="w-full border px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-400" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Expiry Date</label>
                    <input type="date" name="expiry_date" class="w-full border px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-400">
                </div>
            </div>

            <!-- Certificate Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Issuing Authority *</label>
                    <select name="issuing_authority" class="w-full border px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-400" required>
                        <option value="">Select Issuing Authority</option>
                        @foreach($issuingAuthorities as $authority)
                            <option value="{{ $authority->name }}">{{ $authority->name }}</option>
                        @endforeach
                    </select>
                    @if($issuingAuthorities->isEmpty())
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle"></i> 
                            No issuing authorities available. 
                            <a href="{{ route('settings.index') }}" class="text-blue-600 hover:underline">Add authorities in Settings</a>
                        </p>
                    @endif
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Certificate Number</label>
                    <input type="text" name="certificate_number" class="w-full border px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-pink-400">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block mb-1 font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full border px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400" placeholder="Enter certificate description..."></textarea>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block mb-1 font-medium text-gray-700">Certificate File *</label>
                <input type="file" name="certificate_file" class="w-full border px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400" accept=".pdf,.jpg,.jpeg,.png" required>
                <p class="text-sm text-gray-500 mt-1">Upload PDF, JPG, or PNG file (Max: 5MB)</p>
            </div>

            <!-- Status -->
            <div>
                <label class="block mb-1 font-medium text-gray-700">Status *</label>
                <select name="status" class="w-full border px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400" required>
                    <option value="inactive" selected>Inactive</option>
                    <option value="active">Active</option>
                    <option value="expired">Expired</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center pt-6 border-t">
            <div class="flex gap-3">
                <a href="{{ route('certificates.index', ['restaurant_id' => $restaurantId]) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="fas fa-list"></i> View All Certificates
                </a>
                <a href="{{ route('restaurants.show', $restaurantId) }}" 
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Back to Restaurant
                </a>
            </div>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 flex items-center gap-1">
                <i class="fas fa-save"></i> Save Certificate
            </button>
        </div>
    </form>
</div>
@endsection





