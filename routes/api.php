<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantAuthController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\MenuCategoryController;

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
    Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);
    
    // Protected admin routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/restaurants', [RestaurantController::class, 'store']);
        Route::put('/restaurants/{id}', [RestaurantController::class, 'update']);
        Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy']);
        Route::post('/restaurants/{id}/toggle-status', [RestaurantController::class, 'toggleStatus']);
        Route::post('/restaurants/{id}/toggle-block', [RestaurantController::class, 'toggleBlock']);
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