<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateType;
use App\Models\IssuingAuthority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{
    /**
     * Test endpoint
     */
    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'Certificate API is working!',
            'timestamp' => now()
        ]);
    }

    /**
     * Get all certificates for a restaurant
     */
    public function index(Request $request)
    {
        $query = Certificate::query();

        // Filter by restaurant_id
        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->get('restaurant_id'));
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('certificate_number', 'like', "%{$search}%")
                  ->orWhere('issuing_authority', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $certificates = $query->with('restaurant')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $certificates
        ]);
    }

    /**
     * Create a new certificate
     */
    public function store(Request $request)
    {
        // Debug: Log the request
        \Log::info('Certificate API Store Request:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'issuing_authority' => 'required|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status' => 'required|in:active,inactive,expired,pending',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except(['certificate_file']);

        // Handle file upload
        if ($request->hasFile('certificate_file')) {
            $data['certificate_file'] = $request->file('certificate_file')->store('certificates', 'public');
        }

        $certificate = Certificate::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Certificate created successfully',
            'data' => [
                'certificate' => $this->formatCertificate($certificate)
            ]
        ], 201);
    }

    /**
     * Get a specific certificate
     */
    public function show($id)
    {
        $certificate = Certificate::with('restaurant')->find($id);

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'certificate' => $this->formatCertificate($certificate)
            ]
        ]);
    }

    /**
     * Update a certificate
     */
    public function update(Request $request, $id)
    {
        $certificate = Certificate::find($id);

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'issue_date' => 'sometimes|required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'issuing_authority' => 'sometimes|required|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status' => 'sometimes|required|in:active,inactive,expired,pending',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except(['certificate_file']);

        // Handle file upload
        if ($request->hasFile('certificate_file')) {
            // Delete old file if exists
            if ($certificate->certificate_file) {
                Storage::disk('public')->delete($certificate->certificate_file);
            }
            $data['certificate_file'] = $request->file('certificate_file')->store('certificates', 'public');
        }

        $certificate->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Certificate updated successfully',
            'data' => [
                'certificate' => $this->formatCertificate($certificate->fresh())
            ]
        ]);
    }

    /**
     * Delete a certificate
     */
    public function destroy($id)
    {
        $certificate = Certificate::find($id);

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found'
            ], 404);
        }

        // Delete file if exists
        if ($certificate->certificate_file) {
            Storage::disk('public')->delete($certificate->certificate_file);
        }

        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Certificate deleted successfully'
        ]);
    }

    /**
     * Get certificate types
     */
    public function getCertificateTypes()
    {
        $types = CertificateType::where('is_active', true)->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Certificate types retrieved successfully',
            'data' => $types
        ]);
    }

    /**
     * Get issuing authorities
     */
    public function getIssuingAuthorities()
    {
        $authorities = IssuingAuthority::where('is_active', true)->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Issuing authorities retrieved successfully',
            'data' => $authorities
        ]);
    }

    /**
     * View certificate with ID in request body
     */
    public function viewWithIdInBody(Request $request)
    {
        try {
            // Debug: Check what data is being received
            $requestData = $request->all();
            
            // Validate the incoming request to ensure 'id' is present
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'debug_request_data' => $requestData
                ], 422);
            }

            // Retrieve the certificate by ID from the request body
            $certificateId = $request->input('id');
            
            // Debug: Log what ID we're actually receiving
            \Log::info('Certificate View Request', [
                'received_id' => $certificateId,
                'request_all' => $request->all(),
                'content_type' => $request->header('Content-Type')
            ]);
            
            // Check if certificate exists
            $certificate = Certificate::with('restaurant')->find($certificateId);
            
            if (!$certificate) {
                // Debug: Log all available certificate IDs
                $allCertificateIds = Certificate::pluck('id')->toArray();
                $totalCertificates = Certificate::count();
                $latestCertificate = Certificate::latest()->first();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate not found',
                    'error' => "Certificate with ID {$certificateId} does not exist in the database",
                    'debug' => [
                        'requested_id' => $certificateId,
                        'available_ids' => $allCertificateIds,
                        'total_certificates' => $totalCertificates,
                        'latest_certificate_id' => $latestCertificate ? $latestCertificate->id : 'none',
                        'latest_certificate_name' => $latestCertificate ? $latestCertificate->name : 'none'
                    ]
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Certificate retrieved successfully',
                'data' => [
                    'certificate' => $this->formatCertificate($certificate)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve certificate',
                'error' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }

    /**
     * Update certificate with ID in request body
     */
    public function updateWithIdInBody(Request $request)
    {
        try {
            // Debug: Check what data is being received
            $requestData = $request->all();
            
            // Validate the incoming request to ensure 'id' is present
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'name' => 'sometimes|required|string|max:255',
                'type' => 'sometimes|required|string|max:255',
                'issue_date' => 'sometimes|required|date',
                'expiry_date' => 'nullable|date|after:issue_date',
                'issuing_authority' => 'sometimes|required|string|max:255',
                'certificate_number' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'image' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
                'status' => 'sometimes|required|in:active,inactive,expired,pending',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'debug_request_data' => $requestData
                ], 422);
            }

            // Retrieve the certificate by ID from the request body
            $certificateId = $request->input('id');
            
            // Debug: Log what ID we're actually receiving
            \Log::info('Certificate Update Request', [
                'received_id' => $certificateId,
                'request_all' => $request->all(),
                'content_type' => $request->header('Content-Type'),
                'database_name' => \DB::connection()->getDatabaseName(),
                'total_certificates' => Certificate::count(),
                'certificate_exists' => Certificate::find($certificateId) ? 'YES' : 'NO'
            ]);
            
            // Check if certificate exists
            $certificate = Certificate::find($certificateId);
            
            if (!$certificate) {
                // Debug: Log all available certificate IDs
                $allCertificateIds = Certificate::pluck('id')->toArray();
                $totalCertificates = Certificate::count();
                $latestCertificate = Certificate::latest()->first();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate not found',
                    'error' => "Certificate with ID {$certificateId} does not exist in the database",
                    'debug' => [
                        'requested_id' => $certificateId,
                        'available_ids' => $allCertificateIds,
                        'total_certificates' => $totalCertificates,
                        'latest_certificate_id' => $latestCertificate ? $latestCertificate->id : 'none',
                        'latest_certificate_name' => $latestCertificate ? $latestCertificate->name : 'none'
                    ]
                ], 404);
            }

            $data = $request->except(['id', 'certificate_file', 'image']);

            // Handle file upload
            if ($request->hasFile('certificate_file')) {
                // Delete old file if exists
                if ($certificate->certificate_file && Storage::disk('public')->exists($certificate->certificate_file)) {
                    Storage::disk('public')->delete($certificate->certificate_file);
                }
                $data['certificate_file'] = $request->file('certificate_file')->store('certificates', 'public');
            }
            
            // Handle image upload (if separate image field)
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($certificate->certificate_file && Storage::disk('public')->exists($certificate->certificate_file)) {
                    Storage::disk('public')->delete($certificate->certificate_file);
                }
                $data['certificate_file'] = $request->file('image')->store('certificates', 'public');
            }

            // Update certificate
            $certificate->update($data);

            // Load relationship for response
            $certificate->load('restaurant');
            
            return response()->json([
                'success' => true,
                'message' => 'Certificate updated successfully',
                'data' => [
                    'certificate' => $this->formatCertificate($certificate)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update certificate',
                'error' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }

    /**
     * Delete certificate with ID in request body
     */
    public function deleteWithIdInBody(Request $request)
    {
        try {
            // Debug: Check what data is being received
            $requestData = $request->all();
            
            // Validate the incoming request to ensure 'id' is present
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'debug_request_data' => $requestData
                ], 422);
            }

            // Retrieve the certificate by ID from the request body
            $certificateId = $request->input('id');
            
            // Debug: Log what ID we're actually receiving
            \Log::info('Certificate Delete Request', [
                'received_id' => $certificateId,
                'request_all' => $request->all(),
                'content_type' => $request->header('Content-Type'),
                'database_name' => \DB::connection()->getDatabaseName(),
                'total_certificates' => Certificate::count(),
                'certificate_exists' => Certificate::find($certificateId) ? 'YES' : 'NO'
            ]);
            
            // Check if certificate exists
            $certificate = Certificate::find($certificateId);
            
            if (!$certificate) {
                // Debug: Log all available certificate IDs
                $allCertificateIds = Certificate::pluck('id')->toArray();
                $totalCertificates = Certificate::count();
                $latestCertificate = Certificate::latest()->first();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Certificate not found',
                    'error' => "Certificate with ID {$certificateId} does not exist in the database",
                    'debug' => [
                        'requested_id' => $certificateId,
                        'available_ids' => $allCertificateIds,
                        'total_certificates' => $totalCertificates,
                        'latest_certificate_id' => $latestCertificate ? $latestCertificate->id : 'none',
                        'latest_certificate_name' => $latestCertificate ? $latestCertificate->name : 'none'
                    ]
                ], 404);
            }

            // Delete certificate file if exists
            if ($certificate->certificate_file && Storage::disk('public')->exists($certificate->certificate_file)) {
                Storage::disk('public')->delete($certificate->certificate_file);
            }

            // Delete certificate
            $certificate->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Certificate deleted successfully',
                'data' => [
                    'deleted_certificate_id' => $certificateId,
                    'deleted_certificate_name' => $certificate->name
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete certificate',
                'error' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }

    /**
     * Format certificate data for API response
     */
    private function formatCertificate($certificate)
    {
        return [
            'id' => $certificate->id,
            'restaurant_id' => $certificate->restaurant_id,
            'name' => $certificate->name,
            'type' => $certificate->type,
            'issue_date' => $certificate->issue_date,
            'expiry_date' => $certificate->expiry_date,
            'issuing_authority' => $certificate->issuing_authority,
            'certificate_number' => $certificate->certificate_number,
            'description' => $certificate->description,
            'certificate_file' => $certificate->certificate_file ? asset('storage/' . $certificate->certificate_file) : null,
            'status' => $certificate->status,
            'created_at' => $certificate->created_at,
            'updated_at' => $certificate->updated_at,
            'restaurant' => $certificate->restaurant ? [
                'id' => $certificate->restaurant->id,
                'business_name' => $certificate->restaurant->business_name,
                'legal_name' => $certificate->restaurant->legal_name,
            ] : null
        ];
    }

    /**
     * Mobile App - Get certificate list
     */
    public function mobileList(Request $request)
    {
        $query = Certificate::query();

        // Filter by restaurant_id (required for mobile)
        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->get('restaurant_id'));
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('certificate_number', 'like', "%{$search}%")
                  ->orWhere('issuing_authority', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }

        // Get certificates with restaurant info
        $certificates = $query->with('restaurant')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Certificates retrieved successfully',
            'data' => $certificates
        ]);
    }

    /**
     * Mobile App - Get single certificate
     */
    public function mobileShow($id)
    {
        $certificate = Certificate::with('restaurant')->find($id);

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Certificate retrieved successfully',
            'data' => $certificate
        ]);
    }

    /**
     * Mobile App - Create certificate
     */
    public function mobileCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'issuing_authority' => 'required|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,expired,pending',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except(['certificate_file']);
        $certificate = Certificate::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Certificate created successfully',
            'data' => $certificate
        ], 201);
    }

    /**
     * Mobile App - Update certificate
     */
    public function mobileUpdate(Request $request, $id)
    {
        $certificate = Certificate::find($id);

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'issue_date' => 'sometimes|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'issuing_authority' => 'sometimes|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive,expired,pending',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $certificate->update($request->only([
            'name', 'type', 'issue_date', 'expiry_date', 
            'issuing_authority', 'certificate_number', 
            'description', 'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Certificate updated successfully',
            'data' => $certificate
        ]);
    }

    /**
     * Mobile App - Delete certificate
     */
    public function mobileDelete($id)
    {
        $certificate = Certificate::find($id);

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found'
            ], 404);
        }

        // Delete file if exists
        if ($certificate->certificate_file) {
            Storage::disk('public')->delete($certificate->certificate_file);
        }

        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Certificate deleted successfully'
        ]);
    }

    /**
     * POST method for certificate list
     */
    public function postList(Request $request)
    {
        $query = Certificate::query();

        // Filter by restaurant_id (from body)
        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->get('restaurant_id'));
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('certificate_number', 'like', "%{$search}%")
                  ->orWhere('issuing_authority', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }

        // Get certificates with restaurant info - NO LIMIT, get all
        $certificates = $query->with('restaurant')->orderBy('created_at', 'desc')->get();

        // Debug: Log the query and results
        \Log::info('Certificate List Query', [
            'restaurant_id' => $request->get('restaurant_id'),
            'total_found' => $certificates->count(),
            'certificate_ids' => $certificates->pluck('id')->toArray()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Certificates retrieved successfully',
            'data' => $certificates,
            'meta' => [
                'total_count' => $certificates->count(),
                'restaurant_id' => $request->get('restaurant_id')
            ]
        ]);
    }
}
