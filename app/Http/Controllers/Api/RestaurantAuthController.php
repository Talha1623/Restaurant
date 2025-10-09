<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Exception;

class RestaurantAuthController extends Controller
{
    /**
     * Restaurant Registration
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // Basic Info
                'legal_name' => 'required|string|max:255',
                'business_name' => 'required|string|max:255',
                'email' => 'required|email|unique:restaurants,email',
                'restaurant_password' => 'required|string|min:6',
                'phone' => 'required|string|max:20',
                'contact_person' => 'required|string|max:255',
                
                // Address Info
                'address_line1' => 'required|string|max:500',
                'city' => 'required|string|max:100',
                'postcode' => 'required|string|max:20',
                
                // Business Hours
                'opening_time' => 'required|date_format:H:i',
                'closing_time' => 'required|date_format:H:i',
                'min_order' => 'required|numeric|min:0',
                'status' => 'required|in:active,inactive',
                
                // Optional Fields
                'cuisine_tags' => 'nullable|string|max:255',
                'delivery_zone' => 'nullable|string|max:50',
                'delivery_postcode' => 'nullable|string|max:20',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already exists',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            // Handle file uploads
            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo')->store('restaurant-logos', 'public');
            }
            
            if ($request->hasFile('banner')) {
                $data['banner'] = $request->file('banner')->store('restaurant-banners', 'public');
            }

            // Hash password and map to correct field name
            $data['password'] = Hash::make($data['restaurant_password']);
            unset($data['restaurant_password']);

            $restaurant = Restaurant::create($data);

            $token = $restaurant->createToken('restaurant-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Restaurant registered successfully',
               
                    'token' => $token
                
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restaurant Login
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'restaurant_password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $restaurant = Restaurant::where('email', $request->email)->first();

            if (!$restaurant || !Hash::check($request->restaurant_password, $restaurant->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password'
                ], 401);
            }

            // Check if restaurant is active
            if ($restaurant->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your restaurant account is currently inactive. Please contact our support team to reactivate your account. Email us at test@example.com or Call our helpline at 123-456-7890.'
                ], 403);
            }

            // Check if restaurant is blocked (safe check)
            if (isset($restaurant->blocked) && $restaurant->blocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your restaurant account has been blocked due to violations of our policies. For more details or to resolve the issue, please Email us at test@example.com or Call our helpline at 123-456-7890.'
                ], 403);
            }

            $token = $restaurant->createToken('restaurant-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'restaurant' => [
                        'id' => $restaurant->id,
                        'legal_name' => $restaurant->legal_name,
                        'business_name' => $restaurant->business_name,
                        'email' => $restaurant->email,
                        'phone' => $restaurant->phone,
                        'contact_person' => $restaurant->contact_person,
                        'address_line1' => $restaurant->address_line1,
                        'city' => $restaurant->city,
                        'postcode' => $restaurant->postcode,
                        'opening_time' => $restaurant->opening_time,
                        'closing_time' => $restaurant->closing_time,
                        'min_order' => $restaurant->min_order,
                        'status' => $restaurant->status,
                        'cuisine_tags' => $restaurant->cuisine_tags,
                        'delivery_zone' => $restaurant->delivery_zone,
                        'delivery_postcode' => $restaurant->delivery_postcode,
                        'logo' => $restaurant->logo ? asset('storage/' . $restaurant->logo) : null,
                        'banner' => $restaurant->banner ? asset('storage/' . $restaurant->banner) : null
                    ],
                    'token' => $token
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restaurant Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get Restaurant Profile
     */
    public function profile(Request $request)
    {
        $restaurant = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'restaurant' => [
                    'id' => $restaurant->id,
                    'legal_name' => $restaurant->legal_name,
                    'business_name' => $restaurant->business_name,
                    'email' => $restaurant->email,
                    'phone' => $restaurant->phone,
                    'contact_person' => $restaurant->contact_person,
                    'address_line1' => $restaurant->address_line1,
                    'city' => $restaurant->city,
                    'postcode' => $restaurant->postcode,
                    'opening_time' => $restaurant->opening_time,
                    'closing_time' => $restaurant->closing_time,
                    'min_order' => $restaurant->min_order,
                    'status' => $restaurant->status,
                    'cuisine_tags' => $restaurant->cuisine_tags,
                    'delivery_zone' => $restaurant->delivery_zone,
                    'delivery_postcode' => $restaurant->delivery_postcode,
                    'logo' => $restaurant->logo ? asset('storage/' . $restaurant->logo) : null,
                    'banner' => $restaurant->banner ? asset('storage/' . $restaurant->banner) : null,
                    'created_at' => $restaurant->created_at,
                    'updated_at' => $restaurant->updated_at
                ]
            ]
        ]);
    }

    /**
     * Forgot Password
     */
    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:restaurants,email'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $restaurant = Restaurant::where('email', $request->email)->first();

            // Check if restaurant is active
            if ($restaurant->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your restaurant account is currently inactive. Please contact our support team to reactivate your account. Email us at test@example.com or Call our helpline at 123-456-7890.'
                ], 403);
            }

            // Check if restaurant is blocked
            if (isset($restaurant->blocked) && $restaurant->blocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your restaurant account has been blocked due to violations of our policies. For more details or to resolve the issue, please Email us at test@example.com or Call our helpline at 123-456-7890.'
                ], 403);
            }

            // Generate reset token
            $resetToken = Str::random(60);
            $restaurant->update([
                'reset_token' => $resetToken,
                'reset_token_expires_at' => now()->addHours(1) // Token expires in 1 hour
            ]);

            // Send reset email (in real app, you would send actual email)
            // For now, we'll return the token for testing
            return response()->json([
                'success' => true,
                'message' => 'Password reset link has been sent to your email address. Please check your inbox and follow the instructions to reset your password.',
                'data' => [
                    'reset_token' => $resetToken, // Remove this in production
                    'expires_at' => $restaurant->reset_token_expires_at
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset Password
     */
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:restaurants,email',
                'reset_token' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $restaurant = Restaurant::where('email', $request->email)
                ->where('reset_token', $request->reset_token)
                ->where('reset_token_expires_at', '>', now())
                ->first();

            if (!$restaurant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired reset token. Please request a new password reset.'
                ], 400);
            }

            // Update password and clear reset token
            $restaurant->update([
                'password' => Hash::make($request->new_password),
                'reset_token' => null,
                'reset_token_expires_at' => null
            ]);

            // Revoke all existing tokens
            $restaurant->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password has been reset successfully. Please login with your new password.'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
