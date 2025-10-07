<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    /**
     * Get all active sliders (public - for mobile app)
     */
    public function index()
    {
        try {
            $sliders = Slider::where('is_active', true)
                ->orderBy('order')
                ->orderBy('created_at')
                ->get();

            $formattedSliders = $sliders->map(function ($slider) {
                return $this->formatSlider($slider);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedSliders
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sliders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single slider by ID
     */
    public function show($id)
    {
        try {
            $slider = Slider::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $this->formatSlider($slider)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Slider not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get all sliders including inactive (admin only)
     */
    public function all()
    {
        try {
            $sliders = Slider::orderBy('order')->orderBy('created_at')->get();

            $formattedSliders = $sliders->map(function ($slider) {
                return $this->formatSlider($slider);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedSliders
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sliders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new slider (admin only)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
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
                'title' => $request->title,
                'description' => $request->description,
                'is_active' => true,
                'order' => Slider::max('order') + 1,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('sliders', 'public');
            }

            $slider = Slider::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Slider created successfully',
                'data' => $this->formatSlider($slider)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create slider',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update slider (admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $slider = Slider::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
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
                'title' => $request->title,
                'description' => $request->description,
            ];

            if ($request->hasFile('image')) {
                // Delete old image
                if ($slider->image) {
                    Storage::disk('public')->delete($slider->image);
                }
                $data['image'] = $request->file('image')->store('sliders', 'public');
            }

            $slider->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Slider updated successfully',
                'data' => $this->formatSlider($slider)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update slider',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete slider (admin only)
     */
    public function destroy($id)
    {
        try {
            $slider = Slider::findOrFail($id);

            // Delete image
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }

            $slider->delete();

            return response()->json([
                'success' => true,
                'message' => 'Slider deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete slider',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle slider status (admin only)
     */
    public function toggle($id)
    {
        try {
            $slider = Slider::findOrFail($id);
            $slider->update([
                'is_active' => !$slider->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Slider status updated successfully',
                'data' => $this->formatSlider($slider)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle slider status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format slider data for API response
     */
    private function formatSlider($slider)
    {
        return [
            'id' => $slider->id,
            'title' => $slider->title,
            'description' => $slider->description,
            'image' => $slider->image,
            'image_url' => $slider->image ? Storage::url($slider->image) : null,
            'is_active' => $slider->is_active,
            'order' => $slider->order,
            'created_at' => $slider->created_at,
            'updated_at' => $slider->updated_at
        ];
    }
}