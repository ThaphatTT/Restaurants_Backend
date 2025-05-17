<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RestaurantService;

class RestaurantController extends Controller
{
    protected $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    public function search(Request $request)
    {
        //รับ data จาก request
        $keyword = $request->query('keyword', '');

        //รอรับ resp จาก searchResturants
        $results = $this->restaurantService->searchRestaurants($keyword);

        return response()->json($results);
    }
}
