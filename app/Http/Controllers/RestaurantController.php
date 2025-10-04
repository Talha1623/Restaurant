<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('legal_name', 'like', '%' . $request->search . '%')
                    ->orWhere('business_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('contact_person', 'like', '%' . $request->search . '%');
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cuisine_tags')) {
            $query->where('cuisine_tags', 'like', '%' . $request->cuisine_tags . '%');
        }

        // Sort
        if ($request->filled('sort')) {
            if ($request->sort === 'latest') {
                $query->latest();
            } elseif ($request->sort === 'oldest') {
                $query->oldest();
            } elseif ($request->sort === 'az') {
                $query->orderBy('name', 'asc');
            } elseif ($request->sort === 'za') {
                $query->orderBy('name', 'desc');
            }
        }

        // Pagination (preserve query string)
        $restaurants = $query->paginate(10)->withQueryString();

        // Stats to show in top boxes
        $totalRestaurants = Restaurant::count();
        $activeRestaurants = Restaurant::where('status', 'active')->count();
        $inactiveRestaurants = Restaurant::where('status', 'inactive')->count();
        
        // Count restaurants with different criteria
        $withContactPerson = Restaurant::whereNotNull('contact_person')->count();
        
        // Distinct cuisine tags count (exclude NULL/empty)
        $cuisines = Restaurant::whereNotNull('cuisine_tags')
            ->where('cuisine_tags', '<>', '')
            ->distinct()
            ->count('cuisine_tags');

        // Latest added restaurant (optional)
        $latestRestaurant = Restaurant::latest()->first();

        return view('restaurants.index', compact(
            'restaurants',
            'totalRestaurants',
            'activeRestaurants',
            'inactiveRestaurants',
            'withContactPerson',
            'cuisines',
            'latestRestaurant'
        ));
    }

    public function create()
    {
        return view('restaurants.create');
    }

    public function store(Request $request)
    {
        // Debug: Log the request data
        \Log::info('Restaurant store request data:', $request->all());
        
        try {
            $request->validate([
                'legal_name'       => 'required|string|max:255',
                'business_name'    => 'required|string|max:255',
                'email'            => 'required|email|unique:restaurants,email',
                'password'         => 'required|string|min:6',
                'phone'            => 'required|string|max:20',
                'contact_person'   => 'required|string|max:255',
                'address'          => 'nullable|string|max:500',
                'address_line1'    => 'required|string|max:255',
                'city'             => 'required|string|max:100',
                'postcode'         => 'required|string|max:20',
                'opening_time'     => 'required|date_format:H:i',
                'closing_time'     => 'required|date_format:H:i',
                'cuisine_tags'     => 'nullable|string|max:500',
                'delivery_zone'    => 'nullable|string|max:100',
                'delivery_postcode' => 'nullable|string|max:20',
                'min_order'        => 'required|numeric|min:0',
                'status'           => 'required|in:active,inactive',
                'logo'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'banner'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Restaurant creation error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'An error occurred while creating the restaurant: ' . $e->getMessage())->withInput();
        }

        $data = $request->except(['logo', 'banner']);

        // Hash password
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // File Uploads
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('restaurants/logos', 'public');
        }
        
        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('restaurants/banners', 'public');
        }

        try {
            $restaurant = Restaurant::create($data);
            \Log::info('Restaurant created successfully:', ['id' => $restaurant->id, 'name' => $restaurant->business_name]);
            return redirect()->route('restaurants.index')->with('success', 'Restaurant created successfully.');
        } catch (\Exception $e) {
            \Log::error('Database error during restaurant creation:', ['message' => $e->getMessage(), 'data' => $data, 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Restaurant $restaurant)
    {
        return view('restaurants.show', compact('restaurant'));
    }

    public function edit(Restaurant $restaurant)
    {
        return view('restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'legal_name'       => 'nullable|string|max:255',
            'business_name'    => 'nullable|string|max:255',
            'email'            => 'required|email|unique:restaurants,email,' . $restaurant->id,
            'password'         => 'nullable|string|min:6',
            'phone'            => 'required|string|max:20',
            'contact_person'   => 'required|string|max:255',
            'address'          => 'nullable|string|max:500',
            'address_line1'    => 'required|string|max:255',
            'city'             => 'required|string|max:100',
            'postcode'         => 'required|string|max:20',
            'opening_time'     => 'required|date_format:H:i',
            'closing_time'     => 'required|date_format:H:i',
            'cuisine_tags'     => 'nullable|string|max:500',
            'delivery_zone'    => 'nullable|string|max:100',
            'delivery_postcode' => 'nullable|string|max:20',
            'min_order'        => 'required|numeric|min:0',
            'status'           => 'required|in:active,inactive',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['logo', 'banner']);

        // Hash password if provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']); // Don't update password if not provided
        }

        // File Uploads (replace old files if new uploaded)
        if ($request->hasFile('logo')) {
            if ($restaurant->logo && Storage::disk('public')->exists($restaurant->logo)) {
                Storage::disk('public')->delete($restaurant->logo);
            }
            $data['logo'] = $request->file('logo')->store('restaurants/logos', 'public');
        }
        
        if ($request->hasFile('banner')) {
            if ($restaurant->banner && Storage::disk('public')->exists($restaurant->banner)) {
                Storage::disk('public')->delete($restaurant->banner);
            }
            $data['banner'] = $request->file('banner')->store('restaurants/banners', 'public');
        }

        $restaurant->update($data);

        return redirect()->route('restaurants.index')->with('success', 'Restaurant updated successfully.');
    }

    public function destroy(Restaurant $restaurant)
    {
        // ✅ Purani file bhi delete ho jaye
        if ($restaurant->logo && Storage::disk('public')->exists($restaurant->logo)) {
            Storage::disk('public')->delete($restaurant->logo);
        }

        $restaurant->delete();

        return redirect()->route('restaurants.index')->with('success', 'Restaurant deleted successfully.');
    }

    public function toggleStatus(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $restaurant->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $restaurant->status
        ]);
    }

    public function toggleBlock(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'blocked' => 'required|boolean'
        ]);

        $restaurant->update([
            'blocked' => $request->blocked
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Block status updated successfully',
            'blocked' => $restaurant->blocked
        ]);
    }

    public function menu(Restaurant $restaurant)
    {
        // Sample menu data for demonstration
        $menuCategories = [
            'Starters' => [
                ['name' => 'Soup of the Day', 'price' => '£5.99', 'description' => 'Fresh seasonal soup with crusty bread'],
                ['name' => 'Garlic Bread', 'price' => '£3.99', 'description' => 'Toasted bread with garlic butter and herbs'],
                ['name' => 'Bruschetta', 'price' => '£4.99', 'description' => 'Toasted bread topped with tomatoes and basil'],
            ],
            'Main Course' => [
                ['name' => 'Fish & Chips', 'price' => '£12.99', 'description' => 'Fresh cod in crispy batter with chunky chips'],
                ['name' => 'Sunday Roast Beef', 'price' => '£18.50', 'description' => 'Slow-roasted beef with Yorkshire pudding'],
                ['name' => 'Chicken Tikka Masala', 'price' => '£14.99', 'description' => 'Creamy curry with basmati rice'],
                ['name' => 'Beef Wellington', 'price' => '£24.99', 'description' => 'Beef fillet wrapped in puff pastry'],
            ],
            'Desserts' => [
                ['name' => 'Sticky Toffee Pudding', 'price' => '£6.99', 'description' => 'Warm sponge with toffee sauce'],
                ['name' => 'Apple Crumble', 'price' => '£5.99', 'description' => 'Traditional crumble with custard'],
                ['name' => 'Chocolate Fudge Cake', 'price' => '£6.99', 'description' => 'Rich chocolate cake with fudge'],
            ],
            'Beverages' => [
                ['name' => 'Soft Drinks', 'price' => '£2.50', 'description' => 'Coca-Cola, Sprite, Fanta'],
                ['name' => 'Coffee/Tea', 'price' => '£2.99', 'description' => 'Freshly brewed coffee or tea'],
                ['name' => 'Fresh Juice', 'price' => '£3.50', 'description' => 'Orange, Apple, or Pineapple juice'],
            ]
        ];

        return view('restaurants.menu', compact('restaurant', 'menuCategories'));
    }

    public function orders(Restaurant $restaurant)
    {
        // Sample orders data for demonstration
        $orders = [
            [
                'id' => 'ORD-001',
                'customer' => 'John Smith',
                'phone' => '+44 7123 456789',
                'items' => 'Fish & Chips, Cola',
                'quantity' => '2',
                'amount' => '28.47',
                'status' => 'Pending',
                'status_class' => 'bg-orange-100 text-orange-800',
                'time' => '2 min ago',
                'payment_status' => 'Paid',
                'special_instructions' => 'Extra crispy chips, no salt'
            ],
            [
                'id' => 'ORD-002',
                'customer' => 'Sarah Johnson',
                'phone' => '+44 7234 567890',
                'items' => 'Chicken Tikka Masala, Rice',
                'quantity' => '1',
                'amount' => '14.99',
                'status' => 'Preparing',
                'status_class' => 'bg-blue-100 text-blue-800',
                'time' => '15 min ago',
                'payment_status' => 'Paid'
            ],
            [
                'id' => 'ORD-003',
                'customer' => 'Mike Wilson',
                'phone' => '+44 7345 678901',
                'items' => 'Beef Wellington, Wine',
                'quantity' => '1',
                'amount' => '32.98',
                'status' => 'Ready',
                'status_class' => 'bg-green-100 text-green-800',
                'time' => '25 min ago',
                'payment_status' => 'Paid'
            ],
            [
                'id' => 'ORD-004',
                'customer' => 'Emma Brown',
                'phone' => '+44 7456 789012',
                'items' => 'Sunday Roast, Gravy',
                'quantity' => '2',
                'amount' => '37.00',
                'status' => 'Delivered',
                'status_class' => 'bg-green-100 text-green-800',
                'time' => '1 hour ago',
                'payment_status' => 'Paid'
            ],
            [
                'id' => 'ORD-005',
                'customer' => 'David Lee',
                'phone' => '+44 7567 890123',
                'items' => 'Soup, Garlic Bread',
                'quantity' => '1',
                'amount' => '9.98',
                'status' => 'Cancelled',
                'status_class' => 'bg-red-100 text-red-800',
                'time' => '2 hours ago',
                'payment_status' => 'Refunded'
            ],
            [
                'id' => 'ORD-006',
                'customer' => 'Lisa Taylor',
                'phone' => '+44 7678 901234',
                'items' => 'Sticky Toffee Pudding, Tea',
                'quantity' => '1',
                'amount' => '9.98',
                'status' => 'Pending',
                'status_class' => 'bg-orange-100 text-orange-800',
                'time' => '5 min ago',
                'payment_status' => 'Pending'
            ],
            [
                'id' => 'ORD-007',
                'customer' => 'Tom Anderson',
                'phone' => '+44 7789 012345',
                'items' => 'Apple Crumble, Coffee',
                'quantity' => '1',
                'amount' => '8.98',
                'status' => 'Preparing',
                'status_class' => 'bg-blue-100 text-blue-800',
                'time' => '10 min ago',
                'payment_status' => 'Paid'
            ],
            [
                'id' => 'ORD-008',
                'customer' => 'Rachel Green',
                'phone' => '+44 7890 123456',
                'items' => 'Chocolate Fudge Cake, Juice',
                'quantity' => '1',
                'amount' => '10.49',
                'status' => 'Ready',
                'status_class' => 'bg-green-100 text-green-800',
                'time' => '20 min ago',
                'payment_status' => 'Paid'
            ]
        ];

        return view('restaurants.orders', compact('restaurant', 'orders'));
    }

    public function reviews(Restaurant $restaurant)
    {
        // Sample reviews data for demonstration
        $reviews = [
            [
                'id' => 1,
                'customer' => 'Sarah Johnson',
                'rating' => 5,
                'review' => 'Absolutely fantastic! The Fish & Chips were perfectly crispy and the service was excellent. Will definitely be coming back soon!',
                'order_items' => 'Fish & Chips, Cola',
                'order_id' => 'ORD-001',
                'date' => '2 days ago',
                'verified' => true,
                'response' => 'Thank you Sarah! We\'re delighted you enjoyed your meal. Looking forward to serving you again!'
            ],
            [
                'id' => 2,
                'customer' => 'Mike Wilson',
                'rating' => 4,
                'review' => 'Great food and atmosphere. The Chicken Tikka Masala was delicious, though it could have been a bit spicier. Overall a good experience.',
                'order_items' => 'Chicken Tikka Masala, Rice',
                'order_id' => 'ORD-002',
                'date' => '1 week ago',
                'verified' => true,
                'response' => null
            ],
            [
                'id' => 3,
                'customer' => 'Emma Brown',
                'rating' => 5,
                'review' => 'The Sunday Roast was incredible! Perfectly cooked beef and the Yorkshire pudding was amazing. Highly recommend this place.',
                'order_items' => 'Sunday Roast Beef, Gravy',
                'order_id' => 'ORD-003',
                'date' => '1 week ago',
                'verified' => true,
                'response' => 'Thank you Emma! We\'re so glad you enjoyed our Sunday Roast. It\'s one of our signature dishes!'
            ],
            [
                'id' => 4,
                'customer' => 'David Lee',
                'rating' => 3,
                'review' => 'Food was okay, but the service was quite slow. Had to wait 45 minutes for our order. The food quality was decent though.',
                'order_items' => 'Beef Wellington, Wine',
                'order_id' => 'ORD-004',
                'date' => '2 weeks ago',
                'verified' => true,
                'response' => 'We apologize for the wait time David. We\'re working on improving our service speed. Thank you for your feedback.'
            ],
            [
                'id' => 5,
                'customer' => 'Lisa Taylor',
                'rating' => 5,
                'review' => 'Amazing desserts! The Sticky Toffee Pudding was to die for. The staff was very friendly and the atmosphere was lovely.',
                'order_items' => 'Sticky Toffee Pudding, Tea',
                'order_id' => 'ORD-005',
                'date' => '2 weeks ago',
                'verified' => true,
                'response' => 'Thank you Lisa! We\'re thrilled you loved our Sticky Toffee Pudding. It\'s made fresh daily!'
            ],
            [
                'id' => 6,
                'customer' => 'Tom Anderson',
                'rating' => 4,
                'review' => 'Good food overall. The Apple Crumble was excellent, but the coffee could be better. Nice cozy atmosphere.',
                'order_items' => 'Apple Crumble, Coffee',
                'order_id' => 'ORD-006',
                'date' => '3 weeks ago',
                'verified' => false,
                'response' => null
            ],
            [
                'id' => 7,
                'customer' => 'Rachel Green',
                'rating' => 2,
                'review' => 'Disappointed with the service. Food took too long and when it arrived, it was cold. The staff seemed overwhelmed.',
                'order_items' => 'Chocolate Fudge Cake, Juice',
                'order_id' => 'ORD-007',
                'date' => '3 weeks ago',
                'verified' => true,
                'response' => 'We sincerely apologize for this experience Rachel. This is not our usual standard. Please contact us directly so we can make this right.'
            ],
            [
                'id' => 8,
                'customer' => 'James Smith',
                'rating' => 5,
                'review' => 'Outstanding! Every dish was perfect. The staff went above and beyond to make our anniversary dinner special. Highly recommended!',
                'order_items' => 'Beef Wellington, Wine, Dessert',
                'order_id' => 'ORD-008',
                'date' => '1 month ago',
                'verified' => true,
                'response' => 'Thank you James! We\'re honored to have been part of your special anniversary celebration. Congratulations!'
            ],
            [
                'id' => 9,
                'customer' => 'Anna Williams',
                'rating' => 4,
                'review' => 'Lovely restaurant with great ambiance. The Fish & Chips were good, though the portion could be larger for the price.',
                'order_items' => 'Fish & Chips, Soft Drink',
                'order_id' => 'ORD-009',
                'date' => '1 month ago',
                'verified' => true,
                'response' => 'Thank you Anna! We appreciate your feedback about portion size. We\'ll review our serving sizes.'
            ],
            [
                'id' => 10,
                'customer' => 'Chris Davis',
                'rating' => 5,
                'review' => 'Best restaurant in town! The Sunday Roast is absolutely divine. The staff is friendly and the atmosphere is perfect for family dinners.',
                'order_items' => 'Sunday Roast Beef, Yorkshire Pudding',
                'order_id' => 'ORD-010',
                'date' => '1 month ago',
                'verified' => true,
                'response' => 'Thank you Chris! We\'re so pleased you think we\'re the best in town. We love serving families like yours!'
            ]
        ];

        return view('restaurants.reviews', compact('restaurant', 'reviews'));
    }

    public function analytics(Restaurant $restaurant)
    {
        // Sample analytics data for demonstration
        $analytics = [
            'total_revenue' => 12450,
            'order_volume' => 156,
            'average_order_value' => 79.8,
            'new_customers' => 23,
            'revenue_growth' => 15,
            'order_growth' => 8,
            'aov_growth' => 5,
            'customer_growth' => 12
        ];

        return view('restaurants.analytics', compact('restaurant', 'analytics'));
    }

    public function settings(Restaurant $restaurant)
    {
        // Sample settings data for demonstration
        $settings = [
            'operating_hours' => [
                'monday' => ['open' => '09:00', 'close' => '22:00'],
                'tuesday' => ['open' => '09:00', 'close' => '22:00'],
                'wednesday' => ['open' => '09:00', 'close' => '22:00'],
                'thursday' => ['open' => '09:00', 'close' => '22:00'],
                'friday' => ['open' => '09:00', 'close' => '23:00'],
                'saturday' => ['open' => '10:00', 'close' => '23:00'],
                'sunday' => ['open' => '10:00', 'close' => '21:00']
            ],
            'delivery_settings' => [
                'radius' => 5,
                'minimum_order' => 25,
                'delivery_fee' => 3.99,
                'estimated_time' => 30
            ],
            'payment_methods' => [
                'credit_card' => true,
                'mobile_payment' => true,
                'cash_on_delivery' => false
            ],
            'tax_settings' => [
                'vat_rate' => 20,
                'service_charge' => 10,
                'platform_commission' => 15
            ]
        ];

        return view('restaurants.settings', compact('restaurant', 'settings'));
    }
}
