<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantAuthController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\MenuCategoryController;
use App\Http\Controllers\Api\SecondFlavorController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\RestaurantAddonController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Restaurant Authentication Routes
Route::prefix('restaurants')->group(function () {
    // Public routes (no authentication required)
    Route::post('/register', [RestaurantAuthController::class, 'register']);
    Route::post('/login', [RestaurantAuthController::class, 'login']);
    Route::post('/forgot-password', [RestaurantAuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [RestaurantAuthController::class, 'resetPassword']);
    
    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [RestaurantAuthController::class, 'logout']);
        Route::get('/profile', [RestaurantAuthController::class, 'profile']);
    });
});

// Restaurant Management API Routes
Route::prefix('admin')->group(function () {
    // Public restaurant listing (for customers)
    Route::get('/restaurants', [RestaurantController::class, 'index']);
    Route::post('/restaurants/{id}', [RestaurantController::class, 'show']);
    
    // Protected admin routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/restaurants', [RestaurantController::class, 'store']);
        Route::put('/restaurants/{id}', [RestaurantController::class, 'update']);
        Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy']);
        Route::post('/restaurants/{id}/toggle-status', [RestaurantController::class, 'toggleStatus']);
        Route::post('/restaurants/{id}/toggle-block', [RestaurantController::class, 'toggleBlock']);
    });
});

// Addon Management API Routes (Global - restaurant_id in form-data)
Route::prefix('addons')->middleware('auth:sanctum')->group(function () {
    // Addon create with restaurant_id in form-data
    Route::post('/create', [App\Http\Controllers\Api\RestaurantAddonController::class, 'storeWithIdInBody']);
});

// Restaurant Management API Routes (Global - restaurant_id in form-data)
Route::prefix('restaurants')->group(function () {
    // Restaurant complete details for customers (public - no authentication)
    Route::post('/complete-details', [App\Http\Controllers\Api\RestaurantController::class, 'getCompleteDetails']);
    
    // Restaurant view with ID in form-data (protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/view', [App\Http\Controllers\Api\RestaurantController::class, 'showWithIdInBody']);
    });
});

// Certificate Management API Routes
Route::prefix('certificates')->group(function () {
    // Test endpoint
    Route::get('/test', [CertificateController::class, 'test']);
    
    // Public routes (no authentication required)
    Route::get('/types', [CertificateController::class, 'getCertificateTypes']);
    Route::get('/authorities', [CertificateController::class, 'getIssuingAuthorities']);
    
    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [CertificateController::class, 'index']);  // Changed from GET to POST
        Route::get('/{id}', [CertificateController::class, 'show']);
        Route::put('/{id}', [CertificateController::class, 'update']);
        Route::delete('/{id}', [CertificateController::class, 'destroy']);
    });
    
    // Temporary: Certificate operations without authentication for testing
    Route::get('/test-list', [CertificateController::class, 'index']);
    Route::get('/test-show/{id}', [CertificateController::class, 'show']);
    Route::delete('/test-delete/{id}', [CertificateController::class, 'destroy']);
    
    // Mobile App Certificate APIs (without authentication for testing)
    Route::post('/mobile-list', [CertificateController::class, 'mobileList']);  // Changed to POST
    Route::get('/mobile-show/{id}', [CertificateController::class, 'mobileShow']);
    Route::post('/mobile-create', [CertificateController::class, 'mobileCreate']);
    Route::put('/mobile-update/{id}', [CertificateController::class, 'mobileUpdate']);
    Route::delete('/mobile-delete/{id}', [CertificateController::class, 'mobileDelete']);
    
    // Temporary: Certificate creation without authentication for testing
    Route::post('/', [CertificateController::class, 'store']);
    
    // Custom POST endpoint for certificate list
    Route::post('/list', [CertificateController::class, 'postList']);
    
    // Specific restaurant certificates list
    Route::post('/restaurant-certificates', [CertificateController::class, 'postList']);
    
    // Certificate view with ID in body
    Route::post('/view', [CertificateController::class, 'viewWithIdInBody']);
    
    // Certificate update with ID in body
    Route::put('/update', [CertificateController::class, 'updateWithIdInBody']);
    Route::post('/update', [CertificateController::class, 'updateWithIdInBody']);
    
    // Certificate delete with ID in body
    Route::delete('/delete', [CertificateController::class, 'deleteWithIdInBody']);
    Route::post('/delete', [CertificateController::class, 'deleteWithIdInBody']);
});

// Menu Management API Routes
Route::prefix('restaurants/{restaurant_id}')->middleware('auth:sanctum')->group(function () {
    // Menu form data (categories, addons, static options)
    Route::get('/menu/form-data', [App\Http\Controllers\Api\ApiMenuController::class, 'getFormData']);
    
    // Menu CRUD operations
    Route::apiResource('menus', App\Http\Controllers\Api\ApiMenuController::class);
    
    // Additional menu endpoints
    Route::post('/menus/{menu}/toggle-availability', [App\Http\Controllers\Api\ApiMenuController::class, 'toggleAvailability']);
    Route::get('/menus/stats', [App\Http\Controllers\Api\ApiMenuController::class, 'getStats']);
    
    // Restaurant Category Management
    Route::get('/categories', [App\Http\Controllers\RestaurantCategoryController::class, 'index']);
    Route::post('/categories', [App\Http\Controllers\RestaurantCategoryController::class, 'store']);
    Route::get('/categories/{id}', [App\Http\Controllers\RestaurantCategoryController::class, 'show']);
    Route::put('/categories/{id}', [App\Http\Controllers\RestaurantCategoryController::class, 'update']);
    Route::delete('/categories/{id}', [App\Http\Controllers\RestaurantCategoryController::class, 'destroy']);
    Route::post('/categories/{id}/toggle', [App\Http\Controllers\RestaurantCategoryController::class, 'toggle']);
    
    // Restaurant Addon Management
    Route::get('/addons', [RestaurantAddonController::class, 'index']);
    Route::post('/addons', [RestaurantAddonController::class, 'store']);
    
    Route::get('/addons/{addonId}', [RestaurantAddonController::class, 'show']);
    Route::put('/addons/{addonId}', [RestaurantAddonController::class, 'update']);
    Route::delete('/addons/{addonId}', [RestaurantAddonController::class, 'destroy']);
    Route::post('/addons/{addonId}/toggle', [RestaurantAddonController::class, 'toggle']);
});

// Customer Authentication Routes
Route::prefix('customer')->group(function () {
    // Public routes (no authentication required)
    Route::post('/login', [CustomerAuthController::class, 'login']);
    
    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [CustomerAuthController::class, 'logout']);
        Route::get('/profile', [CustomerAuthController::class, 'profile']);
        Route::put('/profile', [CustomerAuthController::class, 'updateProfile']);
        Route::post('/change-password', [CustomerAuthController::class, 'changePassword']);
    });
});

// Customer Management API Routes
Route::prefix('customers')->group(function () {
    // Public customer registration (no authentication required)
    Route::post('/register', [CustomerController::class, 'register']);
    
    // Public second-flavors for customers (no authentication required)
    Route::get('/second-flavors', [SecondFlavorController::class, 'index']);
    
    // Public customer listing and details (for customers to view their own data)
    Route::get('/', [CustomerController::class, 'index']);
    Route::get('/{id}', [CustomerController::class, 'show']);
    
    // Protected admin routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/{id}', [CustomerController::class, 'update']);
        Route::delete('/{id}', [CustomerController::class, 'destroy']);
        Route::post('/{id}/toggle-status', [CustomerController::class, 'toggleStatus']);
        Route::post('/{id}/toggle-block', [CustomerController::class, 'toggleBlock']);
    });
});

// Menu Management API Routes (without restaurant_id in URL)
Route::prefix('menus')->middleware('auth:sanctum')->group(function () {
    Route::post('/add', [App\Http\Controllers\Api\ApiMenuController::class, 'storeWithoutRestaurantId']);
    Route::post('/list', [App\Http\Controllers\Api\ApiMenuController::class, 'indexWithoutRestaurantId']);
    Route::get('/{id}', [App\Http\Controllers\Api\ApiMenuController::class, 'showWithoutRestaurantId']);
    Route::put('/{id}', [App\Http\Controllers\Api\ApiMenuController::class, 'updateWithoutRestaurantId']);
    Route::put('/update', [App\Http\Controllers\Api\ApiMenuController::class, 'updateWithIdInBody']);
    Route::post('/view', [App\Http\Controllers\Api\ApiMenuController::class, 'viewWithIdInBody']);
    Route::delete('/delete', [App\Http\Controllers\Api\ApiMenuController::class, 'destroyWithoutRestaurantId']);
});

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!', 'timestamp' => now()]);
});

// Super simple menus endpoint - backup
Route::get('/all-menus', function () {
    return response()->json([
        'success' => true,
        'message' => 'Simple menus endpoint working',
        'menus' => \App\Models\Menu::select('id', 'name', 'price', 'restaurant_id')->get()
    ]);
});

// Debug route for menus
Route::get('/menus/debug', function () {
    return response()->json([
        'message' => 'Menus debug endpoint working!',
        'timestamp' => now(),
        'routes_loaded' => true
    ]);
});

// Simple menus endpoint for debugging
Route::get('/menus/simple', function () {
    try {
        // Check if Menu model exists and can be accessed
        $menuCount = \App\Models\Menu::count();
        $restaurantCount = \App\Models\Restaurant::count();
        
        return response()->json([
            'success' => true,
            'message' => 'Database connection working!',
            'data' => [
                'total_menus' => $menuCount,
                'total_restaurants' => $restaurantCount,
                'timestamp' => now()
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Database error',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Get All Menus API Route (Public - for customers) - SIMPLE VERSION
Route::get('/menus/all', function () {
    try {
        // Very simple query - just get all menus
        $menus = \App\Models\Menu::all();
        
        $formattedMenus = [];
        foreach ($menus as $menu) {
            $formattedMenus[] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'price' => $menu->price,
                'currency' => $menu->currency ?? 'GBP',
                'restaurant_id' => $menu->restaurant_id,
                'is_available' => $menu->is_available ?? true,
            ];
        }
        
        return response()->json([
            'success' => true,
            'message' => 'All menus retrieved successfully',
            'total_menus' => count($formattedMenus),
            'menus' => $formattedMenus
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Simplified menus endpoint for debugging
Route::get('/menus/all-simple', function () {
    try {
        // Get all menus without complex relationships first
        $menus = \App\Models\Menu::where('status', 'active')->get();
        
        $formattedMenus = $menus->map(function($menu) {
            return [
                'id' => $menu->id,
                'name' => $menu->name,
                'description' => $menu->description,
                'price' => $menu->price,
                'currency' => $menu->currency ?? 'GBP',
                'is_available' => $menu->is_available ?? true,
            ];
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Menus retrieved successfully',
            'data' => [
                'total_menus' => $menus->count(),
                'menus' => $formattedMenus
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve menus',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Menu by Category API Route (Public - for customers)
Route::post('/menus/by-category', [App\Http\Controllers\Api\ApiMenuController::class, 'getMenusByCategory']);

// Menu by Second Flavor API Route (Public - for customers)
Route::post('/menus/by-flavor', [App\Http\Controllers\Api\ApiMenuController::class, 'getMenusBySecondFlavor']);

// Menu Category Management API Routes
Route::prefix('menu-categories')->group(function () {
    // Public routes (no authentication required)
    Route::get('/', [MenuCategoryController::class, 'index']);
    Route::get('/{id}', [MenuCategoryController::class, 'show']);
    
    // Protected admin routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [MenuCategoryController::class, 'store']);
        Route::put('/{id}', [MenuCategoryController::class, 'update']);
        Route::delete('/{id}', [MenuCategoryController::class, 'destroy']);
        Route::post('/{id}/toggle', [MenuCategoryController::class, 'toggle']);
    });
});

// Second Flavor Management API Routes
Route::prefix('second-flavors')->group(function () {
    // Public routes (no authentication required)
    Route::get('/', [SecondFlavorController::class, 'index']);  // Back to GET method
    Route::get('/{id}', [SecondFlavorController::class, 'show']);
    
    // Protected admin routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/all', [SecondFlavorController::class, 'all']);
        Route::post('/', [SecondFlavorController::class, 'store']);
        Route::put('/{id}', [SecondFlavorController::class, 'update']);
        Route::delete('/{id}', [SecondFlavorController::class, 'destroy']);
        Route::post('/{id}/toggle', [SecondFlavorController::class, 'toggle']);
    });
});

// Slider Management API Routes
Route::prefix('sliders')->group(function () {
    // Public routes (no authentication required)
    Route::get('/', [SliderController::class, 'index']);
    Route::get('/{id}', [SliderController::class, 'show']);
    
    // Protected admin routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/all', [SliderController::class, 'all']);
        Route::post('/', [SliderController::class, 'store']);
        Route::put('/{id}', [SliderController::class, 'update']);
        Route::delete('/{id}', [SliderController::class, 'destroy']);
        Route::post('/{id}/toggle', [SliderController::class, 'toggle']);
    });
});