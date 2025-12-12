<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Thana;

class LocationController extends Controller
{
    public function divisions()
    {

        return ApiResponseHelper::success(Division::all());
    }

    // Fetch districts by division
    public function districts($division_id)
    {
        $districts = District::where('division_id', $division_id)->get();

        return ApiResponseHelper::success($districts,'Districts fetched successfully');
    }

    // Fetch thanas by district
    public function thanas($district_id)
    {
        $thanas = Thana::where('district_id', $district_id)->get();
        return ApiResponseHelper::success($thanas,'Thanas fetched successfully');
    }
}
