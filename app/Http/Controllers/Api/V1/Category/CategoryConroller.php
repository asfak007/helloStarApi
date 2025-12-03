<?php

namespace App\Http\Controllers\Api\V1\Category;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ServiceResource;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class CategoryConroller extends Controller
{
    //
    public function index(){
        $data = Category::all();
        return ApiResponseHelper::success(CategoryResource::collection($data),'Category retrieved successfully');
    }

    public function categoryService($id)
    {
        $data = Service::where('category_id',$id)->get();
        return ApiResponseHelper::success(ServiceResource::collection($data),'Services retrieved successfully');
    }
}
