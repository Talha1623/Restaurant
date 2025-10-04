@extends('layouts.app')

@section('title', $restaurant->name . ' - Menu')

@section('content')
<div class="max-w-6xl mx-auto space-y-4">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <a href="{{ route('restaurants.show', $restaurant) }}" class="hover:text-green-600 transition-colors">
                        <i class="fas fa-arrow-left" style="color: #00d03c;"></i>
                    </a>
                    {{ $restaurant->name }} - Menu
                </h3>
                <p class="text-gray-600 mt-1 text-sm">Explore our delicious selection of dishes and beverages</p>
            </div>
        </div>
    </div>



    <!-- Menu Categories -->
    @foreach($menuCategories as $category => $dishes)
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-3">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                @if($category === 'Starters')
                    <i class="fas fa-leaf"></i>
                @elseif($category === 'Main Course')
                    <i class="fas fa-utensils"></i>
                @elseif($category === 'Desserts')
                    <i class="fas fa-birthday-cake"></i>
                @elseif($category === 'Beverages')
                    <i class="fas fa-glass-martini-alt"></i>
                @endif
                {{ $category }}
            </h2>
        </div>
        
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($dishes as $dish)
                <div class="bg-gray-50 rounded-lg p-3 hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold text-gray-800 text-sm">{{ $dish['name'] }}</h3>
                        <span class="text-green-600 font-bold text-sm">{{ $dish['price'] }}</span>
                    </div>
                    <p class="text-gray-600 text-xs leading-relaxed">{{ $dish['description'] }}</p>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2 mt-3">
                        <button class="px-2 py-1 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition flex items-center gap-1">
                            <i class="fas fa-plus"></i> Add to Order
                        </button>
                        <button class="px-2 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition flex items-center gap-1">
                            <i class="fas fa-heart"></i> Favorite
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach



    <!-- Additional Info -->
    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
            <i class="fas fa-info-circle text-blue-600"></i>
            Menu Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-gray-600">
            <div class="flex items-center gap-2">
                <i class="fas fa-clock text-green-500"></i>
                <span>Menu updated daily</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-leaf text-green-500"></i>
                <span>Vegetarian options available</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-allergies text-orange-500"></i>
                <span>Allergen information on request</span>
            </div>
        </div>
    </div>

    <!-- Halal & Haram Certificates -->
    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b pb-2">
            <i class="fas fa-certificate text-green-600"></i>
            Halal & Haram Certificates
        </h3>
        
        <!-- Halal Certificate Section -->
        <div class="mb-6">
            <h4 class="text-lg font-medium text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                Halal Certification
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center gap-3 mb-3">
                        <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                        <div>
                            <h5 class="font-semibold text-green-800">Halal Certified</h5>
                            <p class="text-sm text-green-600">Verified by UK Halal Authority</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm text-green-700">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            <span>Certificate Number: HAL-UK-2024-001</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            <span>Valid until: December 31, 2024</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            <span>All meat sources verified Halal</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check text-green-500"></i>
                            <span>Separate cooking utensils</span>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center gap-3 mb-3">
                        <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                        <div>
                            <h5 class="font-semibold text-blue-800">Certificate Details</h5>
                            <p class="text-sm text-blue-600">View full certification</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm text-blue-700">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-download text-blue-500"></i>
                            <span>Download PDF Certificate</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-eye text-blue-500"></i>
                            <span>View Online Certificate</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-phone text-blue-500"></i>
                            <span>Contact Certifying Body</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Haram Items Information -->
        <div class="mb-6">
            <h4 class="text-lg font-medium text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-orange-500"></i>
                Haram Items Information
            </h4>
            <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-orange-500 text-xl mt-1"></i>
                    <div class="space-y-3">
                        <p class="text-orange-800 font-medium">Important Notice for Muslim Customers</p>
                        <div class="space-y-2 text-sm text-orange-700">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-times text-orange-500"></i>
                                <span>No pork products served</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-times text-orange-500"></i>
                                <span>No alcohol in food preparation</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-times text-orange-500"></i>
                                <span>No gelatin from non-Halal sources</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-times text-orange-500"></i>
                                <span>No cross-contamination with Haram items</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Halal Menu Categories -->
        <div>
            <h4 class="text-lg font-medium text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-utensils text-green-500"></i>
                Halal Menu Categories
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <i class="fas fa-drumstick-bite text-green-600 text-2xl mb-2"></i>
                    <h5 class="font-semibold text-green-800 mb-2">Halal Meat</h5>
                    <p class="text-xs text-green-600">Chicken, Lamb, Beef</p>
                    <div class="mt-2">
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">100% Halal</span>
                    </div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <i class="fas fa-fish text-green-600 text-2xl mb-2"></i>
                    <h5 class="font-semibold text-green-800 mb-2">Seafood</h5>
                    <p class="text-xs text-green-600">Fish, Prawns, Calamari</p>
                    <div class="mt-2">
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Naturally Halal</span>
                    </div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <i class="fas fa-seedling text-green-600 text-2xl mb-2"></i>
                    <h5 class="font-semibold text-green-800 mb-2">Vegetarian</h5>
                    <p class="text-xs text-green-600">Veg dishes & sides</p>
                    <div class="mt-2">
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Always Halal</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certification Authority -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <h5 class="font-medium text-gray-800 mb-3">Certification Authority</h5>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-building text-gray-500"></i>
                    <span>UK Halal Food Authority</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-phone text-gray-500"></i>
                    <span>+44 20 7123 4567</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-globe text-gray-500"></i>
                    <span>www.ukhalal.org</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Restaurant Services -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-cogs text-purple-600"></i>
            Restaurant Services
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-200">
                <i class="fas fa-truck text-green-600 text-xl"></i>
                <div>
                    <h4 class="font-medium text-gray-800">Home Delivery</h4>
                    <p class="text-xs text-gray-600">30 min delivery</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <i class="fas fa-utensils text-blue-600 text-xl"></i>
                <div>
                    <h4 class="font-medium text-gray-800">Dine-in</h4>
                    <p class="text-xs text-gray-600">Reservations available</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-orange-50 rounded-lg border border-orange-200">
                <i class="fas fa-motorcycle text-orange-600 text-xl"></i>
                <div>
                    <h4 class="font-medium text-gray-800">Takeaway</h4>
                    <p class="text-xs text-gray-600">Quick pickup</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                <div>
                    <h4 class="font-medium text-gray-800">Catering</h4>
                    <p class="text-xs text-gray-600">Events & parties</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Special Offers -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-gift text-red-600"></i>
            Special Offers & Deals
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold">Happy Hours</h4>
                    <span class="text-sm bg-white text-red-500 px-2 py-1 rounded-full">50% OFF</span>
                </div>
                <p class="text-sm opacity-90">All drinks 50% off from 3 PM to 6 PM</p>
                <p class="text-xs mt-2 opacity-75">Valid Monday to Friday</p>
            </div>
            <div class="p-4 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold">Student Discount</h4>
                    <span class="text-sm bg-white text-green-500 px-2 py-1 rounded-full">20% OFF</span>
                </div>
                <p class="text-sm opacity-90">20% off on all food with valid student ID</p>
                <p class="text-xs mt-2 opacity-75">Valid any day</p>
            </div>
        </div>
    </div>

    <!-- Customer Reviews -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-comments text-yellow-600"></i>
            Customer Reviews
        </h3>
        <div class="space-y-4">
            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                    JS
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-medium text-gray-800">John Smith</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">"Amazing food and great service! The Fish & Chips were perfect and the staff was very friendly."</p>
                    <p class="text-xs text-gray-500 mt-2">2 days ago</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-semibold">
                    MS
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-medium text-gray-800">Mary Johnson</h4>
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm">"Lovely atmosphere and delicious Sunday Roast. Highly recommend for family dining!"</p>
                    <p class="text-xs text-gray-500 mt-2">1 week ago</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Restaurant Hours & Contact -->
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-clock text-indigo-600"></i>
            Opening Hours & Contact
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-800 mb-3">Opening Hours</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Monday - Friday</span>
                        <span class="text-gray-800 font-medium">11:00 AM - 10:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Saturday</span>
                        <span class="text-gray-800 font-medium">10:00 AM - 11:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sunday</span>
                        <span class="text-gray-800 font-medium">12:00 PM - 9:00 PM</span>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-3">Contact Information</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-phone text-green-500"></i>
                        <span class="text-gray-800">+44 20 7123 4567</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-envelope text-blue-500"></i>
                        <span class="text-gray-800">info@restaurant.com</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-red-500"></i>
                        <span class="text-gray-800">123 High Street, London</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
