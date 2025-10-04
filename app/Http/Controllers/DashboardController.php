<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rider;
use App\Models\Customer;
use App\Models\Restaurant;

class DashboardController extends Controller
{
    public function index()
    {
        // Counts
        $totalRestaurants = Restaurant::count();
        $activeRestaurants = Restaurant::where('status', 'active')->count();
        $inactiveRestaurants = Restaurant::where('status', 'inactive')->count();
        $activeRiders = Rider::where('status', 'active')->count();
        $inactiveRiders = Rider::where('status', 'inactive')->count();
        $totalCustomers = Customer::count();

        // Last 5 entries
        $recentRestaurants = Restaurant::latest()->take(5)->get();
        $recentRiders = Rider::latest()->take(5)->get();

        // Dynamic chart data (last 7 days)
        $labels = [];
        $restaurantData = [];
        $riderData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('D');
            $restaurantData[] = Restaurant::whereDate('created_at', $date)->count();
            $riderData[] = Rider::whereDate('created_at', $date)->count();
        }

        return view('dashboard', compact(
            'totalRestaurants',
            'activeRestaurants',
            'inactiveRestaurants',
            'activeRiders',
            'inactiveRiders',
            'totalCustomers',
            'recentRestaurants',
            'recentRiders',
            'labels',
            'restaurantData',
            'riderData'
        ));
    }
}
