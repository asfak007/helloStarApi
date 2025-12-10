<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    public function index()
    {
        $coupons = Coupon::with('offer')
            ->where('is_active', true)
            ->get();

        return ApiResponseHelper::success( CouponResource::collection($coupons), 'Active coupons retrieved successfully', 200);
    }


}
