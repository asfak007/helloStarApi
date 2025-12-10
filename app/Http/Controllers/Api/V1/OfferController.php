<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    //
    public function index()
    {
         $offers = Offer::with('service', 'coupons')
            ->where('is_active', true)
            ->get();

        return ApiResponseHelper::success(OfferResource::collection($offers), 'Active offers retrieved successfully', 200);

    }
}
