<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Resources\ReportResource;
use App\Jobs\AnalyzeDamageJob;
use App\Models\Report;
use Illuminate\Http\Request;

/**
 * @group Report Management
 *
 * APIs for managing damage reports
 */
class ReportController extends Controller
{
    /**
     * Get all reports for the authenticated user.
     *
     * @authenticated
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "user": {"id": 1, "name": "User"},
     *       "image_url": "http://...",
     *       "location": {...},
     *       "description": {...},
     *       "damage_assessment": {...}
     *     }
     *   ]
     * }
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $reports = auth()->user()->reports()->latest()->get();
        return response()->json(ReportResource::collection($reports));
    }

    /**
     * Create a new damage report.
     *
     * @authenticated
     * @bodyParam image file required The damage image. Maximum size: 10MB.
     * @bodyParam latitude number required The GPS latitude. Example: 36.2018
     * @bodyParam longitude number required The GPS longitude. Example: 37.1342
     * @bodyParam raw_location string required The location name as entered by user. Example: "حلب السكري"
     * @bodyParam raw_description string optional Additional description. Maximum: 2000 characters.
     * @response 201 {
     *   "data": {
     *     "id": 1,
     *     "status": "pending",
     *     "message": "Report submitted successfully. Processing will start shortly."
     *   }
     * }
     * @response 422 {
     *   "errors": {
     *     "image": ["The image field is required."]
     *   }
     * }
     */
    public function store(StoreReportRequest $request): \Illuminate\Http\JsonResponse
    {
        $imagePath = $request->file('image')->store('reports', 'public');

        $report = Report::create([
            'user_id' => auth()->id(),
            'image_path' => $imagePath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'raw_location' => $request->raw_location,
            'raw_description' => $request->raw_description ?? '',
            'status' => 'pending',
        ]);

        AnalyzeDamageJob::dispatch($report->id);

        return response()->json([
            'data' => [
                'id' => $report->id,
                'status' => 'pending',
                'message' => 'Report submitted successfully. Processing will start shortly.'
            ]
        ], 201);
    }

    /**
     * Get a specific report.
     *
     * @authenticated
     * @urlParam id required The report ID.
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "user": {...},
     *     "image_url": "http://...",
     *     ...
     *   }
     * }
     * @response 404 {"message": "Report not found"}
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $report = auth()->user()->reports()->findOrFail($id);
        return response()->json(ReportResource::make($report));
    }

    /**
     * Delete a specific report.
     *
     * @authenticated
     * @urlParam id required The report ID.
     * @response 200 {"message": "Report deleted successfully"}
     * @response 404 {"message": "Report not found"}
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $report = auth()->user()->reports()->findOrFail($id);
        
        // Delete the image file if it exists
        if ($report->image_path && \Storage::disk('public')->exists($report->image_path)) {
            \Storage::disk('public')->delete($report->image_path);
        }
        
        $report->delete();
        
        return response()->json([
            'message' => 'Report deleted successfully'
        ]);
    }
}
