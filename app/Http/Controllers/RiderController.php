<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    // Index page
    public function index(Request $request)
    {
        $query = Rider::query();

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
                  ->orWhere('vehicle_number', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%")
                  ->orWhere('postcode', 'like', "%{$request->search}%");
            });
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $riders = $query->latest()->paginate(10);

        $totalRiders = Rider::count();
        $activeRiders = Rider::where('status', 'active')->count();
        $inactiveRiders = Rider::where('status', 'inactive')->count();

        return view('riders.index', compact('riders', 'totalRiders', 'activeRiders', 'inactiveRiders'));
    }

    // Create page
    public function create()
    {
        $vehicleTypes = ['Bike', 'Car', 'Van', 'Scooter']; // dropdown options
        return view('riders.create', compact('vehicleTypes'));
    }

    // Store new rider
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required',
            'vehicle_type'   => 'required',
            'status'         => 'required|in:active,inactive',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'license_front_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'license_back_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'insurance_doc'  => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'mot_doc'        => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'right_to_work_doc' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $data = $request->except(['photo','license_front_image','license_back_image','insurance_doc','mot_doc','right_to_work_doc']);

        // Handle uploads
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('riders/photos','public');
        }
        if ($request->hasFile('license_front_image')) {
            $data['license_front_image'] = $request->file('license_front_image')->store('riders/license','public');
        }
        if ($request->hasFile('license_back_image')) {
            $data['license_back_image'] = $request->file('license_back_image')->store('riders/license','public');
        }
        if ($request->hasFile('insurance_doc')) {
            $data['insurance_doc'] = $request->file('insurance_doc')->store('riders/docs','public');
        }
        if ($request->hasFile('mot_doc')) {
            $data['mot_doc'] = $request->file('mot_doc')->store('riders/docs','public');
        }
        if ($request->hasFile('right_to_work_doc')) {
            $data['right_to_work_doc'] = $request->file('right_to_work_doc')->store('riders/docs','public');
        }

        Rider::create($data);

        return redirect()->route('riders.index')->with('success', 'Rider added successfully!');
    }

    // Edit page
    public function edit(Rider $rider)
    {
        $vehicleTypes = ['Bike', 'Car', 'Van', 'Scooter'];
        return view('riders.edit', compact('rider', 'vehicleTypes'));
    }

    // Update rider
    public function update(Request $request, Rider $rider)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required',
            'vehicle_type'   => 'required',
            'status'         => 'required|in:active,inactive',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'license_front_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'license_back_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'insurance_doc'  => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'mot_doc'        => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'right_to_work_doc' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $data = $request->except(['photo','license_front_image','license_back_image','insurance_doc','mot_doc','right_to_work_doc']);

        // Handle uploads (replace old if new file uploaded)
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('riders/photos','public');
        }
        if ($request->hasFile('license_front_image')) {
            $data['license_front_image'] = $request->file('license_front_image')->store('riders/license','public');
        }
        if ($request->hasFile('license_back_image')) {
            $data['license_back_image'] = $request->file('license_back_image')->store('riders/license','public');
        }
        if ($request->hasFile('insurance_doc')) {
            $data['insurance_doc'] = $request->file('insurance_doc')->store('riders/docs','public');
        }
        if ($request->hasFile('mot_doc')) {
            $data['mot_doc'] = $request->file('mot_doc')->store('riders/docs','public');
        }
        if ($request->hasFile('right_to_work_doc')) {
            $data['right_to_work_doc'] = $request->file('right_to_work_doc')->store('riders/docs','public');
        }

        $rider->update($data);

        return redirect()->route('riders.index')->with('success', 'Rider updated successfully!');
    }

    // Delete rider
    public function destroy(Rider $rider)
    {
        $rider->delete();
        return redirect()->route('riders.index')->with('success', 'Rider deleted successfully!');
    }

    // Show rider details
    public function show(Rider $rider)
    {
        return view('riders.show', compact('rider'));
    }

    // Show rider services
    public function services(Rider $rider)
    {
        // Sample data for rider services
        $serviceStats = [
            'total_deliveries' => 156,
            'active_hours' => 42,
            'earnings' => 1250,
            'rating' => 4.8
        ];

        $serviceAreas = [
            'Central London' => ['Covent Garden', 'Soho', 'Mayfair'],
            'East London' => ['Shoreditch', 'Hackney', 'Bethnal Green'],
            'West London' => ['Notting Hill', 'Kensington', 'Chelsea']
        ];

        $deliveryServices = [
            'Food Delivery' => ['Restaurant orders', 'Fast food', 'Gourmet meals'],
            'Grocery Delivery' => ['Supermarket orders', 'Fresh produce', 'Household items'],
            'Package Delivery' => ['Small parcels', 'Documents', 'Gifts'],
            'Express Delivery' => ['Same day delivery', 'Priority orders', 'Urgent items']
        ];

        $pricing = [
            'base_fee' => 3.50,
            'per_km' => 0.80,
            'rush_hour' => 1.20,
            'late_night' => 2.00
        ];

        return view('riders.services', compact('rider', 'serviceStats', 'serviceAreas', 'deliveryServices', 'pricing'));
    }

    // Show rider performance review
    public function performance(Rider $rider)
    {
        // Sample performance data for demonstration
        $performance = [
            'overall_rating' => 4.7,
            'total_deliveries' => 1247,
            'success_rate' => 98.5,
            'total_earnings' => 18450,
            'on_time_rate' => 96.2,
            'customer_satisfaction' => 4.7,
            'distance_covered' => 1247,
            'performance_score' => 94
        ];

        return view('riders.performance', compact('rider', 'performance'));
    }

    // Show rider payment history
    public function paymentHistory(Rider $rider)
    {
        // Sample payment history data for demonstration
        $paymentHistory = [
            'total_earnings' => 45230,
            'this_month' => 8450,
            'last_payment' => 2150,
            'pending_amount' => 1850,
            'payments' => [
                [
                    'id' => 'PAY-001',
                    'date' => '2024-12-15',
                    'amount' => 2150,
                    'method' => 'Bank Transfer',
                    'status' => 'Paid'
                ],
                [
                    'id' => 'PAY-002',
                    'date' => '2024-11-15',
                    'amount' => 1980,
                    'method' => 'Bank Transfer',
                    'status' => 'Paid'
                ],
                [
                    'id' => 'PAY-003',
                    'date' => '2024-10-15',
                    'amount' => 2320,
                    'method' => 'Mobile Payment',
                    'status' => 'Paid'
                ],
                [
                    'id' => 'PAY-004',
                    'date' => '2024-09-15',
                    'amount' => 1750,
                    'method' => 'Bank Transfer',
                    'status' => 'Paid'
                ]
            ]
        ];

        return view('riders.payment-history', compact('rider', 'paymentHistory'));
    }

    // Show rider assign delivery page
    public function assignDelivery(Rider $rider)
    {
        // Sample data for assign delivery page
        $availableDeliveries = 12;
        $todayDeliveries = 8;
        $pendingAssignments = 4;
        $totalEarnings = 156.50;

        return view('riders.assign-delivery', compact('rider', 'availableDeliveries', 'todayDeliveries', 'pendingAssignments', 'totalEarnings'));
    }

    // Show rider earnings page
    public function earnings(Rider $rider)
    {
        // Sample data for earnings page
        $totalEarnings = 45230;
        $thisMonthEarnings = 8450;
        $thisWeekEarnings = 2150;
        $todayEarnings = 185;
        $totalDeliveries = 1247;
        $avgPerDelivery = 36.25;
        $bestDayEarnings = 285;
        $customerRating = 4.7;
        $onTimeRate = 96.2;
        $basePay = 1250;
        $deliveryFees = 3240;
        $tips = 890;
        $bonuses = 450;
        $peakHours1 = 45;
        $peakHours2 = 52;
        $peakHours3 = 38;
        $topArea1 = 1250;
        $topArea2 = 980;
        $topArea3 = 750;

        return view('riders.earnings', compact('rider', 'totalEarnings', 'thisMonthEarnings', 'thisWeekEarnings', 'todayEarnings', 'totalDeliveries', 'avgPerDelivery', 'bestDayEarnings', 'customerRating', 'onTimeRate', 'basePay', 'deliveryFees', 'tips', 'bonuses', 'peakHours1', 'peakHours2', 'peakHours3', 'topArea1', 'topArea2', 'topArea3'));
    }

    // Show rider analytics page
    public function analytics(Rider $rider)
    {
        // Sample data for analytics page
        $onTimeRate = 96.2;
        $customerRating = 4.7;
        $totalDeliveries = 1247;
        $avgDeliveryTime = 18;
        $successRate = 98.5;
        $morningDeliveries = 245;
        $afternoonDeliveries = 456;
        $eveningDeliveries = 389;
        $nightDeliveries = 157;

        return view('riders.analytics', compact('rider', 'onTimeRate', 'customerRating', 'totalDeliveries', 'avgDeliveryTime', 'successRate', 'morningDeliveries', 'afternoonDeliveries', 'eveningDeliveries', 'nightDeliveries'));
    }
}
