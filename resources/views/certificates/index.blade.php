@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <a href="{{ route('restaurants.show', $restaurant->id) }}" class="hover:text-green-600 transition-colors">
                    <i class="fas fa-arrow-left text-green-600 hover:text-green-700"></i>
                </a> 
                Certificates - {{ $restaurant->legal_name ?? 'N/A' }}
            </h3>
            
            <!-- Add New Certificate Button -->
            <a href="{{ route('certificates.create', ['restaurant_id' => $restaurant->id]) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Certificate
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Certificates List -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        @if($certificates->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certificate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($certificates as $certificate)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-certificate text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $certificate->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $certificate->issuing_authority }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $certificate->type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $certificate->issue_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($certificate->expiry_date)
                                    {{ $certificate->expiry_date->format('M d, Y') }}
                                @else
                                    <span class="text-gray-400">No expiry</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($certificate->status == 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif($certificate->status == 'expired')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('certificates.show', $certificate) }}"
                                       class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded-md hover:bg-blue-50 transition">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('certificates.edit', $certificate) }}"
                                       class="text-indigo-600 hover:text-indigo-900 px-3 py-1 rounded-md hover:bg-indigo-50 transition">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('certificates.destroy', $certificate) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 px-3 py-1 rounded-md hover:bg-red-50 transition"
                                                onclick="return confirm('Are you sure you want to delete this certificate?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $certificates->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400">
                    <i class="fas fa-certificate text-6xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No certificates</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new certificate.</p>
                <div class="mt-6">
                    <a href="{{ route('certificates.create', ['restaurant_id' => $restaurant->id]) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-plus -ml-1 mr-2"></i>
                        Add New Certificate
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Restaurant Action Buttons -->
    <div class="bg-white rounded-lg shadow p-3 border border-gray-200 mt-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
            <a href="{{ route('menus.index', ['restaurant_id' => $restaurant->id]) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-utensils"></i> Menu
            </a>
            <a href="{{ route('certificates.index', ['restaurant_id' => $restaurant->id]) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-green-600 hover:bg-green-700">
                <i class="fas fa-certificate"></i> Certificates
            </a>
            <a href="{{ route('restaurants.orders', $restaurant) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
            <a href="{{ route('restaurants.analytics', $restaurant) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-chart-bar"></i> Analytics
            </a>
            <a href="{{ route('restaurants.reviews', $restaurant) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-star"></i> Reviews
            </a>
            <a href="{{ route('restaurants.settings', $restaurant) }}" 
               class="px-2 py-1.5 text-white text-xs rounded-md transition flex items-center justify-center gap-1 bg-black hover:bg-gray-800">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </div>
</div>
@endsection