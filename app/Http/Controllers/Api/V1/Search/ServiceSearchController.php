<?php

namespace App\Http\Controllers\Api\V1\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceSearchController extends Controller
{
    //
    public function serviceFlow(Request $request)
    {
        // Helper: Get filtered services based on category, search, and optional area
        $getFilteredServices = function ($categoryId, $search = null, $areaId = null) {
            $query = Service::query();

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            if ($areaId) {
                $query->whereHas('areas', function($q) use ($areaId) {
                    $q->where('area_id', $areaId);
                });
            }

            return $query->get();
        };

        switch ($request->step) {

            // STEP 1: Select Category (or show all categories if none given)
            case 1:
                $categoryId = $request->category_id ?? null;

                if ($categoryId) {
                    session(['category_id' => $categoryId]);
                    $services = $getFilteredServices($categoryId);

                    return response()->json([
                        'step' => 1,
                        'message' => 'Category selected successfully',
                        'services' => $services
                    ]);
                } else {
                    // Return all categories
                    $categories = Category::all(); // assuming you have a Category model

                    return response()->json([
                        'step' => 1,
                        'message' => 'All categories fetched',
                        'categories' => $categories
                    ]);
                }

            // STEP 2: Keyword Search inside category
            case 2:
                $request->validate(['search' => 'nullable|string']);

                $categoryId = session('category_id');
                session(['search_keyword' => $request->search]);

                $services = $getFilteredServices($categoryId, $request->search);

                return response()->json([
                    'step' => 2,
                    'message' => 'Filtered by search text',
                    'services' => $services
                ]);

            // STEP 3: Check Area Availability
            case 3:
                $request->validate(['area_id' => 'required|integer']);

                $categoryId = session('category_id');
                $searchText = session('search_keyword');

                session(['area_id' => $request->area_id]);

                $services = $getFilteredServices($categoryId, $searchText, $request->area_id);

                return response()->json([
                    'step' => 3,
                    'message' => 'Area availability checked',
                    'services' => $services
                ]);

            // STEP 4: Save Checkout Info
            case 4:
                $request->validate([
                    'service_id' => 'required|integer',
                    'date'       => 'required|date',
                    'time'       => 'required|string'
                ]);

                session([
                    'service_id' => $request->service_id,
                    'date'       => $request->date,
                    'time'       => $request->time
                ]);

                return response()->json([
                    'step' => 4,
                    'message' => 'Checkout data saved',
                    'checkout' => session()->all()
                ]);

            default:
                return response()->json(['error' => 'Invalid step'], 400);
        }
    }


}
