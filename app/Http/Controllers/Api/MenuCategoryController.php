<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of menu categories
     */
    public function index(Request $request)
    {
        try {
            $query = MenuCategory::query();

            // Search filter
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Status filter
            if ($request->has('status') && $request->status !== '') {
                $query->where('is_active', $request->status === 'active');
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $categories = $query->orderBy('name')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Menu categories retrieved successfully',
                'data' => [
                    'categories' => $categories->items(),
                    'pagination' => [
                        'current_page' => $categories->currentPage(),
                        'last_page' => $categories->lastPage(),
                        'per_page' => $categories->perPage(),
                        'total' => $categories->total(),
                        'from' => $categories->firstItem(),
                        'to' => $categories->lastItem()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve menu categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified menu category
     */
    public function show($id)
    {
        try {
            $category = MenuCategory::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu category not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Menu category retrieved successfully',
                'data' => [
                    'category' => $this->formatCategory($category)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve menu category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created menu category
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:menu_categories,name',
                'description' => 'nullable|string|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('menu-categories', $imageName, 'public');
            }

            $category = MenuCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'is_active' => $request->get('is_active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu category created successfully',
                'data' => [
                    'category' => $this->formatCategory($category)
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create menu category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified menu category
     */
    public function update(Request $request, $id)
    {
        try {
            $category = MenuCategory::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu category not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:menu_categories,name,' . $id,
                'description' => 'nullable|string|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle image upload
            $imagePath = $category->image; // Keep existing image
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('menu-categories', $imageName, 'public');
            }

            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'is_active' => $request->get('is_active', $category->is_active)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu category updated successfully',
                'data' => [
                    'category' => $this->formatCategory($category)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified menu category
     */
    public function destroy($id)
    {
        try {
            $category = MenuCategory::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu category not found'
                ], 404);
            }

            // Delete image if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu category deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete menu category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of the menu category
     */
    public function toggle($id)
    {
        try {
            $category = MenuCategory::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu category not found'
                ], 404);
            }

            $category->update(['is_active' => !$category->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Menu category status updated successfully',
                'data' => [
                    'category' => $this->formatCategory($category)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle menu category status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format category data for API response
     */
    private function formatCategory($category)
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'image' => $category->image,
            'image_url' => $category->image ? Storage::url($category->image) : null,
            'is_active' => $category->is_active,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at
        ];
    }
}
