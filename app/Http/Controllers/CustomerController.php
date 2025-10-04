<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    // List all customers with filters
    public function index(Request $request) {
        $query = Customer::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name, email, phone, NI number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search){
                $q->where('first_name','like','%'.$search.'%')
                  ->orWhere('last_name','like','%'.$search.'%')
                  ->orWhere('email','like','%'.$search.'%')
                  ->orWhere('phone','like','%'.$search.'%')
                  ->orWhere('ni_number','like','%'.$search.'%');
            });
        }

        $customers = $query->latest()->paginate(10);

        // Overview cards
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status','active')->count();
        $inactiveCustomers = Customer::where('status','inactive')->count();
        $blockedCustomers = Customer::where('status','blocked')->count();

        return view('customers.index', compact(
            'customers', 'totalCustomers', 'activeCustomers', 'inactiveCustomers', 'blockedCustomers'
        ));
    }

    // Show create form
    public function create() {
        return view('customers.create');
    }

    // Store new customer
    public function store(Request $request) {
        $request->validate([
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'email'=>'required|email|unique:customers,email',
            'phone'=>'nullable|string|max:20',
            'gender'=>'nullable|in:male,female,other,prefer_not_to_say',
            'dob'=>'nullable|date',
            'address_line1'=>'nullable|string',
            'city'=>'required|string',
            'postcode'=>'nullable|string|max:10',
            'country'=>'nullable|string',
            'ni_number'=>'nullable|string|max:20',
            'status'=>'required|in:active,inactive,blocked',
            'loyalty_points'=>'nullable|integer',
            'notes'=>'nullable|string'
        ]);

        Customer::create($request->all());
        return redirect()->route('customers.index')->with('success','Customer added successfully');
    }

    // Show edit form
    public function edit(Customer $customer) {
        return view('customers.edit', compact('customer'));
    }

    // Update existing customer
    public function update(Request $request, Customer $customer) {
        $request->validate([
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'email'=>'required|email|unique:customers,email,'.$customer->id,
            'phone'=>'nullable|string|max:20',
            'gender'=>'nullable|in:male,female,other,prefer_not_to_say',
            'dob'=>'nullable|date',
            'address_line1'=>'nullable|string',
            'city'=>'required|string',
            'postcode'=>'nullable|string|max:10',
            'country'=>'nullable|string',
            'ni_number'=>'nullable|string|max:20',
            'status'=>'required|in:active,inactive,blocked',
            'loyalty_points'=>'nullable|integer',
            'notes'=>'nullable|string'
        ]);

        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success','Customer updated successfully');
    }

    // Delete a customer
    public function destroy(Customer $customer) {
        $customer->delete();
        return redirect()->route('customers.index')->with('success','Customer deleted successfully');
    }

    // Show single customer details
    public function show(Customer $customer) {
        return view('customers.show', compact('customer'));
    }

    // Show customer services
    public function services(Customer $customer) {
        // Sample data for customer services
        $customerStats = [
            'total_orders' => 45,
            'total_spent' => 1250.75,
            'favorite_cuisine' => 'Italian',
            'loyalty_points' => 1250
        ];

        $orderHistory = [
            [
                'id' => 'ORD-001',
                'restaurant' => 'Pizza Palace',
                'items' => 'Margherita Pizza, Garlic Bread',
                'amount' => 28.50,
                'status' => 'Delivered',
                'date' => '2024-01-15'
            ],
            [
                'id' => 'ORD-002',
                'restaurant' => 'Sushi Express',
                'items' => 'California Roll, Miso Soup',
                'amount' => 32.00,
                'status' => 'Delivered',
                'date' => '2024-01-12'
            ],
            [
                'id' => 'ORD-003',
                'restaurant' => 'Burger House',
                'items' => 'Classic Burger, Fries',
                'amount' => 18.75,
                'status' => 'In Progress',
                'date' => '2024-01-14'
            ]
        ];

        $favoriteRestaurants = [
            'Pizza Palace' => ['orders' => 12, 'rating' => 4.8, 'last_order' => '2024-01-15'],
            'Sushi Express' => ['orders' => 8, 'rating' => 4.6, 'last_order' => '2024-01-12'],
            'Burger House' => ['orders' => 6, 'rating' => 4.4, 'last_order' => '2024-01-14'],
            'Indian Spice' => ['orders' => 5, 'rating' => 4.7, 'last_order' => '2024-01-10']
        ];

        $deliveryAddresses = [
            'Home' => [
                'address' => '123 Main Street, London',
                'default' => true,
                'instructions' => 'Ring doorbell twice'
            ],
            'Work' => [
                'address' => '456 Business Park, London',
                'default' => false,
                'instructions' => 'Leave at reception'
            ],
            'Gym' => [
                'address' => '789 Fitness Center, London',
                'default' => false,
                'instructions' => 'Call when arriving'
            ]
        ];

        $paymentMethods = [
            'Visa ending in 1234' => ['type' => 'Credit Card', 'expiry' => '12/25'],
            'PayPal' => ['type' => 'Digital Wallet', 'email' => 'customer@email.com'],
            'Cash on Delivery' => ['type' => 'COD', 'preference' => 'High']
        ];

        $loyaltyRewards = [
            'current_points' => 1250,
            'next_reward' => 'Free Delivery (at 1500 points)',
            'available_rewards' => [
                '£5 off next order (at 1000 points)',
                'Free dessert (at 800 points)',
                'Priority delivery (at 1200 points)'
            ]
        ];

        return view('customers.services', compact(
            'customer', 'customerStats', 'orderHistory', 'favoriteRestaurants', 
            'deliveryAddresses', 'paymentMethods', 'loyaltyRewards'
        ));
    }

    // Show customer delivery addresses page
    public function deliveryAddresses(Customer $customer)
    {
        // Sample data for delivery addresses page
        $totalAddresses = 3;
        $recentDeliveries = 12;
        $successRate = 98;

        return view('customers.delivery-addresses', compact('customer', 'totalAddresses', 'recentDeliveries', 'successRate'));
    }

    // Show customer payment methods page
    public function paymentMethods(Customer $customer)
    {
        // Sample data for payment methods page
        $totalMethods = 3;
        $totalTransactions = 45;
        $successRate = 99;

        return view('customers.payment-methods', compact('customer', 'totalMethods', 'totalTransactions', 'successRate'));
    }

    // Show customer order history page
    public function orderHistory(Customer $customer)
    {
        // Sample data for order history page
        $totalOrders = 28;
        $completedOrders = 24;
        $pendingOrders = 3;
        $totalSpent = 1250;
        $avgOrderValue = 44.64;
        $orderFrequency = 2.3;

        return view('customers.order-history', compact('customer', 'totalOrders', 'completedOrders', 'pendingOrders', 'totalSpent', 'avgOrderValue', 'orderFrequency'));
    }
}
