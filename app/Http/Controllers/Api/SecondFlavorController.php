<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SecondFlavor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SecondFlavorController extends Controller
{
    /**
     * Get all second flavors (public - for mobile app)
     */
    public function index()
    {
        try {
            $flavors = SecondFlavor::where('is_active', true)
                ->orderBy('name')
                ->get();

            $formattedFlavors = $flavors->map(function ($flavor) {
                return $this->formatFlavor($flavor);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedFlavors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch second flavors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single second flavor by ID
     */
    public function show($id)
    {
        try {
            $flavor = SecondFlavor::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $this->formatFlavor($flavor)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Second flavor not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get all second flavors including inactive (admin only)
     */
    public function all()
    {
        try {
            $flavors = SecondFlavor::orderBy('name')->get();

            $formattedFlavors = $flavors->map(function ($flavor) {
                return $this->formatFlavor($flavor);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedFlavors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch second flavors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new second flavor (admin only)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:second_flavors,name',
                // Make image optional to allow creating flavor without image from settings/API
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = [
                'name' => $request->name,
                'is_active' => true,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('second-flavors', 'public');
            }

            $flavor = SecondFlavor::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Second flavor created successfully',
                'data' => $this->formatFlavor($flavor)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create second flavor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update second flavor (admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $flavor = SecondFlavor::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:second_flavors,name,' . $id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = [
                'name' => $request->name,
            ];

            if ($request->hasFile('image')) {
                // Delete old image
                if ($flavor->image) {
                    Storage::disk('public')->delete($flavor->image);
                }
                $data['image'] = $request->file('image')->store('second-flavors', 'public');
            }

            $flavor->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Second flavor updated successfully',
                'data' => $this->formatFlavor($flavor)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update second flavor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete second flavor (admin only)
     */
    public function destroy($id)
    {
        try {
            $flavor = SecondFlavor::findOrFail($id);

            // Delete image
            if ($flavor->image) {
                Storage::disk('public')->delete($flavor->image);
            }

            $flavor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Second flavor deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete second flavor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle second flavor status (admin only)
     */
    public function toggle($id)
    {
        try {
            $flavor = SecondFlavor::findOrFail($id);
            $flavor->update([
                'is_active' => !$flavor->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Second flavor status updated successfully',
                'data' => $this->formatFlavor($flavor)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle second flavor status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format second flavor data for API response
     */
    private function formatFlavor($flavor)
    {
        return [
            'id' => $flavor->id,
            'name' => $flavor->name,
            'image' => $flavor->image,
            'image_url' => $flavor->image ? asset('storage/' . $flavor->image) : null,
            'is_active' => $flavor->is_active,
            'created_at' => $flavor->created_at,
            'updated_at' => $flavor->updated_at
        ];
    }
}

