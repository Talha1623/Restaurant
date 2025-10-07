<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ColdDrinksAddonController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');




Route::resource('restaurants', RestaurantController::class);
Route::post('/restaurants/{restaurant}/toggle-status', [RestaurantController::class, 'toggleStatus'])->name('restaurants.toggle-status');
Route::post('/restaurants/{restaurant}/toggle-block', [RestaurantController::class, 'toggleBlock'])->name('restaurants.toggle-block');
Route::get('/restaurants/{restaurant}/menu', [RestaurantController::class, 'menu'])->name('restaurants.menu');
Route::get('/restaurants/{restaurant}/orders', [RestaurantController::class, 'orders'])->name('restaurants.orders');
Route::get('/restaurants/{restaurant}/reviews', [RestaurantController::class, 'reviews'])->name('restaurants.reviews');
Route::get('/restaurants/{restaurant}/analytics', [RestaurantController::class, 'analytics'])->name('restaurants.analytics');
Route::get('/restaurants/{restaurant}/settings', [RestaurantController::class, 'settings'])->name('restaurants.settings');
Route::resource('riders', RiderController::class);
Route::get('/riders/{rider}/services', [RiderController::class, 'services'])->name('riders.services');
Route::get('/riders/{rider}/performance', [RiderController::class, 'performance'])->name('riders.performance');
Route::get('/riders/{rider}/payment-history', [RiderController::class, 'paymentHistory'])->name('riders.payment-history');
Route::get('/riders/{rider}/assign-delivery', [RiderController::class, 'assignDelivery'])->name('riders.assign-delivery');
Route::get('/riders/{rider}/earnings', [RiderController::class, 'earnings'])->name('riders.earnings');
Route::get('/riders/{rider}/analytics', [RiderController::class, 'analytics'])->name('riders.analytics');

Route::prefix('customers')->group(function() {
    Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/{customer}', [CustomerController::class, 'show'])->name('customers.show'); // ✅ Add this
    Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/{customer}/services', [CustomerController::class, 'services'])->name('customers.services');
    Route::get('/{customer}/delivery-addresses', [CustomerController::class, 'deliveryAddresses'])->name('customers.delivery-addresses');
    Route::get('/{customer}/payment-methods', [CustomerController::class, 'paymentMethods'])->name('customers.payment-methods');
    Route::get('/{customer}/order-history', [CustomerController::class, 'orderHistory'])->name('customers.order-history');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('certificates', CertificateController::class);
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings/certificate-types', [SettingsController::class, 'storeCertificateType'])->name('settings.certificate-types.store');
Route::put('/settings/certificate-types/{id}', [SettingsController::class, 'updateCertificateType'])->name('settings.certificate-types.update');
Route::delete('/settings/certificate-types/{id}', [SettingsController::class, 'deleteCertificateType'])->name('settings.certificate-types.delete');
Route::post('/settings/certificate-types/{id}/toggle', [SettingsController::class, 'toggleCertificateType'])->name('settings.certificate-types.toggle');
Route::post('/settings/issuing-authorities', [SettingsController::class, 'storeIssuingAuthority'])->name('settings.issuing-authorities.store');
Route::put('/settings/issuing-authorities/{id}', [SettingsController::class, 'updateIssuingAuthority'])->name('settings.issuing-authorities.update');
Route::delete('/settings/issuing-authorities/{id}', [SettingsController::class, 'deleteIssuingAuthority'])->name('settings.issuing-authorities.delete');
Route::post('/settings/issuing-authorities/{id}/toggle', [SettingsController::class, 'toggleIssuingAuthority'])->name('settings.issuing-authorities.toggle');

// Cold Drinks Addons routes
Route::post('/settings/cold-drinks-addons', [SettingsController::class, 'storeColdDrinksAddon'])->name('settings.cold-drinks-addons.store');
Route::put('/settings/cold-drinks-addons/{id}', [SettingsController::class, 'updateColdDrinksAddon'])->name('settings.cold-drinks-addons.update');
Route::delete('/settings/cold-drinks-addons/{id}', [SettingsController::class, 'deleteColdDrinksAddon'])->name('settings.cold-drinks-addons.delete');
Route::post('/settings/cold-drinks-addons/{id}/toggle', [SettingsController::class, 'toggleColdDrinksAddon'])->name('settings.cold-drinks-addons.toggle');

// Restaurant Categories routes (Menu Categories)
Route::post('/settings/categories', [SettingsController::class, 'storeCategory'])->name('settings.categories.store');
Route::put('/settings/categories/{id}', [SettingsController::class, 'updateCategory'])->name('settings.categories.update');
Route::delete('/settings/categories/{id}', [SettingsController::class, 'deleteCategory'])->name('settings.categories.destroy');
Route::post('/settings/categories/{id}/toggle', [SettingsController::class, 'toggleCategory'])->name('settings.categories.toggle');

// Second Flavor routes
Route::post('/settings/second-flavor', [SettingsController::class, 'storeSecondFlavor'])->name('settings.second-flavor.store');
Route::put('/settings/second-flavor/{id}', [SettingsController::class, 'updateSecondFlavor'])->name('settings.second-flavor.update');
Route::delete('/settings/second-flavor/{id}', [SettingsController::class, 'deleteSecondFlavor'])->name('settings.second-flavor.destroy');
Route::post('/settings/second-flavor/{id}/toggle', [SettingsController::class, 'toggleSecondFlavor'])->name('settings.second-flavor.toggle');

// Slider routes
Route::post('/settings/slider', [SettingsController::class, 'storeSlider'])->name('settings.slider.store');
Route::put('/settings/slider/{id}', [SettingsController::class, 'updateSlider'])->name('settings.slider.update');
Route::delete('/settings/slider/{id}', [SettingsController::class, 'destroySlider'])->name('settings.slider.destroy');
Route::post('/settings/slider/{id}/toggle', [SettingsController::class, 'toggleSlider'])->name('settings.slider.toggle');

// Menu routes
Route::resource('menus', MenuController::class);

// Restaurant Category routes
Route::post('/restaurant-categories', [App\Http\Controllers\RestaurantCategoryController::class, 'store'])->name('restaurant-categories.store');
Route::put('/restaurant-categories/{id}', [App\Http\Controllers\RestaurantCategoryController::class, 'update'])->name('restaurant-categories.update');
Route::delete('/restaurant-categories/{id}', [App\Http\Controllers\RestaurantCategoryController::class, 'destroy'])->name('restaurant-categories.destroy');
Route::post('/restaurant-categories/{id}/toggle', [App\Http\Controllers\RestaurantCategoryController::class, 'toggle'])->name('restaurant-categories.toggle');

// Ingredient routes
Route::post('/ingredients', [App\Http\Controllers\IngredientController::class, 'store'])->name('ingredients.store');
Route::put('/ingredients/{id}', [App\Http\Controllers\IngredientController::class, 'update'])->name('ingredients.update');
Route::delete('/ingredients/{id}', [App\Http\Controllers\IngredientController::class, 'destroy'])->name('ingredients.destroy');

// Restaurant Addon routes
Route::get('/restaurants/{restaurant}/addons', [App\Http\Controllers\RestaurantAddonController::class, 'index'])->name('restaurants.addons.index');
Route::get('/restaurants/{restaurant}/addons/create', [App\Http\Controllers\RestaurantAddonController::class, 'create'])->name('restaurants.addons.create');
Route::post('/restaurants/{restaurant}/addons', [App\Http\Controllers\RestaurantAddonController::class, 'store'])->name('restaurants.addons.store');
Route::get('/restaurants/{restaurant}/addons/{addon}/edit', [App\Http\Controllers\RestaurantAddonController::class, 'edit'])->name('restaurants.addons.edit');
Route::put('/restaurants/{restaurant}/addons/{addon}', [App\Http\Controllers\RestaurantAddonController::class, 'update'])->name('restaurants.addons.update');
Route::delete('/restaurants/{restaurant}/addons/{addon}', [App\Http\Controllers\RestaurantAddonController::class, 'destroy'])->name('restaurants.addons.destroy');
Route::post('/restaurants/{restaurant}/addons/{addon}/toggle', [App\Http\Controllers\RestaurantAddonController::class, 'toggle'])->name('restaurants.addons.toggle');
