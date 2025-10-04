<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Register a new customer
     */
    public function register(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'phone' => 'nullable|string|max:20',
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
                'address_line1' => 'nullable|string',
                'city' => 'required|string',
                'postcode' => 'nullable|string|max:10',
                'country' => 'nullable|string',
                'username' => 'nullable|string|unique:customers,username',
                'password' => 'required|string|min:6',
                'status' => 'required|in:active,inactive,blocked',
                'registration_date' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create customer data
            $customerData = $request->only([
                'first_name', 'last_name', 'email', 'phone', 'dob', 'gender',
                'address_line1', 'city', 'postcode', 'country', 'username', 'status'
            ]);

            // Hash password
            $customerData['password'] = Hash::make($request->password);

            // Set registration date if not provided
            if (!$request->registration_date) {
                $customerData['registration_date'] = Carbon::now()->toDateString();
            } else {
                $customerData['registration_date'] = $request->registration_date;
            }

            // Create customer
            $customer = Customer::create($customerData);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Customer registered successfully',
                'data' => [
                    'customer' => $this->formatCustomer($customer)
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all customers
     */
    public function index(Request $request)
    {
        try {
            $query = Customer::query();

            // Search filter
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Status filter
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $customers = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Customers retrieved successfully',
                'data' => [
                    'customers' => $customers->items(),
                    'pagination' => [
                        'current_page' => $customers->currentPage(),
                        'last_page' => $customers->lastPage(),
                        'per_page' => $customers->perPage(),
                        'total' => $customers->total(),
                        'from' => $customers->firstItem(),
                        'to' => $customers->lastItem()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific customer
     */
    public function show($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer retrieved successfully',
                'data' => [
                    'customer' => $this->formatCustomer($customer)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update customer
     */
    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // Validation rules
            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:customers,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
                'address_line1' => 'nullable|string',
                'city' => 'sometimes|required|string',
                'postcode' => 'nullable|string|max:10',
                'country' => 'nullable|string',
                'username' => 'nullable|string|unique:customers,username,' . $id,
                'password' => 'sometimes|required|string|min:6',
                'status' => 'sometimes|required|in:active,inactive,blocked',
                'registration_date' => 'nullable|date'
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
                'first_name', 'last_name', 'email', 'phone', 'dob', 'gender',
                'address_line1', 'city', 'postcode', 'country', 'username', 'status', 'registration_date'
            ]);

            // Hash password if provided
            if ($request->has('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $customer->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => [
                    'customer' => $this->formatCustomer($customer)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete customer
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle customer status
     */
    public function toggleStatus($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // Toggle between active and inactive
            $customer->status = $customer->status === 'active' ? 'inactive' : 'active';
            $customer->save();

            return response()->json([
                'success' => true,
                'message' => 'Customer status updated successfully',
                'data' => [
                    'customer' => $this->formatCustomer($customer)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Block/Unblock customer
     */
    public function toggleBlock($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // Toggle between blocked and active
            $customer->status = $customer->status === 'blocked' ? 'active' : 'blocked';
            $customer->save();

            return response()->json([
                'success' => true,
                'message' => 'Customer block status updated successfully',
                'data' => [
                    'customer' => $this->formatCustomer($customer)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer block status',
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
