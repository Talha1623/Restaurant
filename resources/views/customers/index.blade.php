@extends('layouts.app')
@section('title', 'Customers ')
@section('content')
<div class="space-y-4">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-users"></i> Customers
        </h1>
        <a href="{{ route('customers.create') }}" 
           class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md shadow hover:bg-green-700">
            <i class="fas fa-plus mr-1"></i> Add Customer
        </a>
    </div>

    <!-- Flash message -->
    @if(session('success'))
    <div id="flashMessage" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            const flash = document.getElementById('flashMessage');
            if(flash) flash.style.display = 'none';
        }, 5000);
    </script>
    @endif

    <!-- 📊 Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Total -->
        <div class="p-3 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg shadow flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium opacity-80">Total</h3>
                <p class="text-xl font-bold">{{ $totalCustomers }}</p>
            </div>
            <i class="fas fa-users text-lg"></i>
        </div>

        <!-- Active -->
        <div class="p-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium opacity-80 flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> Active
                </h3>
                <p class="text-xl font-bold">{{ $activeCustomers }}</p>
            </div>
            <i class="fas fa-user-check text-lg"></i>
        </div>

        <!-- Inactive -->
        <div class="p-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg shadow flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium opacity-80 flex items-center gap-1">
                    <i class="fas fa-times-circle"></i> Inactive
                </h3>
                <p class="text-xl font-bold">{{ $inactiveCustomers }}</p>
            </div>
            <i class="fas fa-user-times text-lg"></i>
        </div>

        <!-- Blocked -->
        <div class="p-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow flex items-center justify-between">
            <div>
                <h3 class="text-xs font-medium opacity-80 flex items-center gap-1">
                    <i class="fas fa-ban"></i> Blocked
                </h3>
                <p class="text-xl font-bold">{{ $blockedCustomers }}</p>
            </div>
            <i class="fas fa-user-slash text-lg"></i>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..." 
               class="w-full md:w-1/3 px-3 py-1.5 text-sm border rounded-md focus:ring-1 focus:ring-green-200">

        <div class="flex flex-wrap gap-1">
            <select name="status" class="px-2 py-1 text-xs border rounded focus:ring-1 focus:ring-indigo-400">
                <option value="">All Status</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                <option value="blocked" {{ request('status')=='blocked'?'selected':'' }}>Blocked</option>
            </select>

            <select name="sort" class="px-2 py-1 text-xs border rounded focus:ring-1 focus:ring-indigo-400">
                <option value="">Sort</option>
                <option value="latest" {{ request('sort')=='latest'?'selected':'' }}>Latest</option>
                <option value="oldest" {{ request('sort')=='oldest'?'selected':'' }}>Oldest</option>
                <option value="az" {{ request('sort')=='az'?'selected':'' }}>A-Z</option>
                <option value="za" {{ request('sort')=='za'?'selected':'' }}>Z-A</option>
            </select>

            <button type="submit" class="px-2 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                <i class="fas fa-filter mr-1"></i>Filter
            </button>
        </div>
    </form>

    <!-- 📋 Table -->
    <div class="bg-white shadow rounded overflow-hidden border border-gray-200">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 border-b">
                <tr class="text-gray-600 text-xs text-center">
                    <th class="px-2 py-2 border-r">ID</th>
                    <th class="px-2 py-2 border-r">Name</th>
                    <th class="px-2 py-2 border-r">Email</th>
                    <th class="px-2 py-2 border-r">Phone</th>
                    <th class="px-2 py-2 border-r">City</th>
                    <th class="px-2 py-2 border-r">Status</th>
                    <th class="px-2 py-2 border-r">Date</th>
                    <th class="px-2 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-xs">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-2 py-2 border-r text-center">{{ $customer->id }}</td>
                    <td class="px-2 py-2 border-r font-medium text-indigo-600">
                        {{ $customer->first_name }} {{ $customer->last_name }}
                    </td>
                    <td class="px-2 py-2 border-r">{{ $customer->email }}</td>
                    <td class="px-2 py-2 border-r">{{ $customer->phone ?? '-' }}</td>
                    <td class="px-2 py-2 border-r">{{ $customer->city ?? '-' }}</td>
                    <td class="px-2 py-2 border-r text-center">
                        @if($customer->status=='active')
                            <span class="px-1.5 py-0.5 text-xs rounded-full bg-green-100 text-green-700">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        @elseif($customer->status=='inactive')
                            <span class="px-1.5 py-0.5 text-xs rounded-full bg-orange-100 text-orange-700">
                                <i class="fas fa-times-circle"></i> Inactive
                            </span>
                        @elseif($customer->status=='blocked')
                            <span class="px-1.5 py-0.5 text-xs rounded-full bg-red-100 text-red-700">
                                <i class="fas fa-ban"></i> Blocked
                            </span>
                        @endif
                    </td>
                    <td class="px-2 py-2 border-r text-center">{{ $customer->created_at->format('d/m/Y') }}</td>
                    <td class="px-2 py-2 text-center space-x-1">
                        <!-- View -->
                        <a href="{{ route('customers.show',$customer) }}" class="inline-flex items-center px-1.5 py-0.5 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                            <i class="fas fa-eye"></i>
                        </a>

                        <!-- Edit -->
                        <a href="{{ route('customers.edit',$customer) }}" class="inline-flex items-center px-1.5 py-0.5 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- Delete -->
                        <form action="{{ route('customers.destroy',$customer) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Delete this customer?')" 
                                class="inline-flex items-center px-1.5 py-0.5 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-2 py-3 text-center text-gray-500 text-xs">No customers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $customers->links() }}</div>

</div>
@endsection
