<?php

namespace App\Http\Controllers\Api\V1\Service;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceAvaibilabilityRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    //
    public function index()
    {
        $id = request()->id;

        if ($id) {
            $service = Service::with([

                'processes',
                'requirements',
                'faqs',
                'reviews'
            ])->find($id);

            if (!$service) {
                return ApiResponseHelper::notFound('Service not found');
            }

            return ApiResponseHelper::success(new ServiceResource($service), 'Service retrieved successfully');
        }

        $services = Service::with([

            'processes',
            'requirements',
            'faqs',
            'reviews'
        ])->get();

        return ApiResponseHelper::success(ServiceResource::collection($services), 'Services retrieved successfully');
    }

    public function popularServices()
    {
        $services = Service::with('category')
            ->withCount('orders')
            ->orderBy('orders_count', 'DESC')
            ->take(10)
            ->get();

        return ApiResponseHelper::success(ServiceResource::collection($services),'Services retrieved successfully');
    }

    public function demandingServices()
    {
        $services = Service::with('category')
            ->withCount('orders')
            ->withAvg('reviews', 'rating')
            ->orderByRaw('(orders_count * 0.6) + (reviews_avg_rating * 0.4) DESC')
            ->take(10)
            ->get();

        return ApiResponseHelper::success(ServiceResource::collection($services),'Services retrieved successfully');
    }

    public function checkAvailability(ServiceAvaibilabilityRequest $request)
    {
        $validated = $request->validated();

        $service_id = $validated['service_id'];
        $thana   = $validated['thana_id'];

        $service = Service::with('areas')->find($request->service_id);

        $isAvailable = $service->areas()->where('area_id', $request->thana_id)->exists();

        return response()->json([
            'service_id' => $request->service_id,
            'area_id' => $request->area_id,
            'available' => $isAvailable ? 'available' : 'Service is NOT available in this area.'
        ], 200);
    }
}
