<?php

namespace App\Http\Controllers\Api\V1\Service;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
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
                'category',
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
            'category',
            'processes',
            'requirements',
            'faqs',
            'reviews'
        ])->get();

        return ApiResponseHelper::success(ServiceResource::collection($services), 'Services retrieved successfully');
    }
}
