<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    /**
     * Customer Login
     */
    public function login(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find customer by email
            $customer = Customer::where('email', $request->email)->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Check password
            if (!Hash::check($request->password, $customer->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Check if customer is active
            if ($customer->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Account is not active. Please contact support.'
                ], 401);
            }

            // Create token
            $token = $customer->createToken('customer-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'customer' => $this->formatCustomer($customer),
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Customer Logout
     */
    public function logout(Request $request)
    {
        try {
            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Customer Profile
     */
    public function profile(Request $request)
    {
        try {
            $customer = $request->user();

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => [
                    'customer' => $this->formatCustomer($customer)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Customer Profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $customer = $request->user();

            // Validation rules
            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
                'address_line1' => 'nullable|string',
                'city' => 'sometimes|required|string',
                'postcode' => 'nullable|string|max:10',
                'country' => 'nullable|string',
                'username' => 'nullable|string|unique:customers,username,' . $customer->id,
                'password' => 'sometimes|required|string|min:6'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update customer data
            $updateData = $request->only([
                'first_name', 'last_name', 'phone', 'dob', 'gender',
                'address_line1', 'city', 'postcode', 'country', 'username'
            ]);

            // Hash password if provided
            if ($request->has('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $customer->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'customer' => $this->formatCustomer($customer)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        try {
            $customer = $request->user();

            // Validation rules
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6',
                'confirm_password' => 'required|string|same:new_password'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check current password
            if (!Hash::check($request->current_password, $customer->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }

            // Update password
            $customer->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to change password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format customer data for API response
     */
    private function formatCustomer($customer)
    {
        return [
            'id' => $customer->id,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'dob' => $customer->dob,
            'gender' => $customer->gender,
            'address_line1' => $customer->address_line1,
            'city' => $customer->city,
            'postcode' => $customer->postcode,
            'country' => $customer->country,
            'username' => $customer->username,
            'status' => $customer->status,
            'registration_date' => $customer->registration_date,
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at
        ];
    }
}
