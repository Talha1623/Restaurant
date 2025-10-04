@extends('layouts.app')

@section('title', $restaurant->name . ' - Reviews')
@section('content')
<div class="max-w-7xl mx-auto space-y-4 p-3">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <a href="{{ route('restaurants.show', $restaurant) }}" class="hover:text-green-600 transition-colors">
                        <i class="fas fa-arrow-left" style="color: #00d03c;"></i>
                    </a>
                    {{ $restaurant->name }} - Reviews
                </h3>
                <p class="text-gray-600 text-sm mt-1">Customer feedback and ratings</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-2">
                <button class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-1">
                    <i class="fas fa-plus"></i> Request Review
                </button>
                <button class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-1">
                    <i class="fas fa-download"></i> Export Reviews
                </button>
            </div>
        </div>
    </div>

    <!-- Reviews Statistics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Total Reviews -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Reviews</p>
                    <p class="text-2xl font-bold text-gray-800">89</p>
                    <p class="text-xs text-green-600">+5 this week</p>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-comments text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Average Rating</p>
                    <p class="text-2xl font-bold text-gray-800">4.3</p>
                    <div class="flex items-center gap-1 mt-1">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= 4)
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                @elseif($i == 5)
                                    <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-xs text-gray-600">(4.3/5)</span>
                    </div>
                </div>
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-star text-yellow-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">This Month</p>
                    <p class="text-2xl font-bold text-gray-800">23</p>
                    <p class="text-xs text-green-600">+3 from last month</p>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-calendar-alt text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Response Rate -->
        <div class="bg-white rounded-lg shadow p-3 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-1">Response Rate</p>
                    <p class="text-2xl font-bold text-gray-800">87%</p>
                    <p class="text-xs text-green-600">Good engagement</p>
                </div>
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-reply text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Rating Distribution</h3>
        <div class="space-y-3">
            @for($rating = 5; $rating >= 1; $rating--)
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1 w-16">
                        <span class="text-sm font-medium text-gray-700">{{ $rating }}</span>
                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                    </div>
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $rating == 5 ? '65%' : ($rating == 4 ? '20%' : ($rating == 3 ? '10%' : ($rating == 2 ? '3%' : '2%'))) }}"></div>
                    </div>
                    <span class="text-sm text-gray-600 w-12 text-right">
                        {{ $rating == 5 ? '58' : ($rating == 4 ? '18' : ($rating == 3 ? '9' : ($rating == 2 ? '3' : '1'))) }}
                    </span>
                </div>
            @endfor
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" placeholder="Search reviews by customer name or content..." 
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="flex flex-wrap gap-2">
                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    <option>All Ratings</option>
                    <option>5 Stars</option>
                    <option>4 Stars</option>
                    <option>3 Stars</option>
                    <option>2 Stars</option>
                    <option>1 Star</option>
                </select>
                
                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    <option>All Time</option>
                    <option>This Week</option>
                    <option>This Month</option>
                    <option>Last 3 Months</option>
                </select>
                
                <select class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    <option>All Reviews</option>
                    <option>With Response</option>
                    <option>Without Response</option>
                    <option>Verified Orders</option>
                </select>
                
                <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Desktop Table Header -->
        <div class="hidden lg:block bg-gray-50 px-6 py-3 border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-600">
                <div class="col-span-3">Customer & Rating</div>
                <div class="col-span-4">Review</div>
                <div class="col-span-2">Order Details</div>
                <div class="col-span-1">Date</div>
                <div class="col-span-2">Actions</div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="divide-y divide-gray-200">
            @foreach($reviews as $review)
            <!-- Desktop View -->
            <div class="hidden lg:block px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="grid grid-cols-12 gap-4 items-start">
                    <div class="col-span-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $review['customer'] }}</p>
                                <div class="flex items-center gap-1 mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review['rating'])
                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        @else
                                            <i class="far fa-star text-gray-300 text-xs"></i>
                                        @endif
                                    @endfor
                                    <span class="text-xs text-gray-600 ml-1">{{ $review['rating'] }}/5</span>
                                </div>
                                @if($review['verified'])
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                        <i class="fas fa-check-circle mr-1"></i> Verified
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-span-4">
                        <p class="text-sm text-gray-800 mb-2">{{ $review['review'] }}</p>
                        @if($review['response'])
                            <div class="bg-gray-50 p-3 rounded-lg mt-2">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-reply text-green-600 text-xs"></i>
                                    <span class="text-xs font-medium text-gray-700">Restaurant Response</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $review['response'] }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-800">{{ $review['order_items'] }}</p>
                        <p class="text-xs text-gray-500">Order #{{ $review['order_id'] }}</p>
                    </div>
                    <div class="col-span-1">
                        <span class="text-sm text-gray-600">{{ $review['date'] }}</span>
                    </div>
                    <div class="col-span-2">
                        <div class="flex gap-1">
                            @if(!$review['response'])
                                <button class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition" title="Respond">
                                    <i class="fas fa-reply"></i>
                                </button>
                            @endif
                            <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="px-2 py-1 bg-orange-500 text-white text-xs rounded hover:bg-orange-600 transition" title="Flag">
                                <i class="fas fa-flag"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile View -->
            <div class="lg:hidden p-4 border-b border-gray-200">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $review['customer'] }}</p>
                            <div class="flex items-center gap-1 mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review['rating'])
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-gray-300 text-xs"></i>
                                    @endif
                                @endfor
                                <span class="text-xs text-gray-600 ml-1">{{ $review['rating'] }}/5</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-600">{{ $review['date'] }}</span>
                        @if($review['verified'])
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                <i class="fas fa-check-circle mr-1"></i> Verified
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <p class="text-sm text-gray-800 mb-2">{{ $review['review'] }}</p>
                    @if($review['response'])
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-reply text-green-600 text-xs"></i>
                                <span class="text-xs font-medium text-gray-700">Restaurant Response</span>
                            </div>
                            <p class="text-sm text-gray-700">{{ $review['response'] }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="space-y-1 mb-3">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shopping-bag text-orange-500 w-4"></i>
                        <span class="text-sm text-gray-800">{{ $review['order_items'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-hashtag text-gray-500 w-4"></i>
                        <span class="text-sm text-gray-800">Order #{{ $review['order_id'] }}</span>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    @if(!$review['response'])
                        <button class="flex-1 px-3 py-2 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition flex items-center justify-center gap-1">
                            <i class="fas fa-reply"></i> Respond
                        </button>
                    @endif
                    <button class="px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="px-3 py-2 bg-orange-500 text-white text-sm rounded-lg hover:bg-orange-600 transition">
                        <i class="fas fa-flag"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-200">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-600">
                Showing 1 to 10 of 89 reviews
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <button class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm">1</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">2</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">3</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
