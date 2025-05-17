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
        try
        {
            // รับ data จาก request
            $keyword = $request->query('keyword', '');
            // Log keyword ที่ผู้ใช้ค้นหา
            \Log::channel('search')->info("🔍 ค้นหา: {$keyword}");
            // รอรับ resp จาก service
            $results = $this->restaurantService->searchRestaurants($keyword);
            // Log response บางส่วน
            \Log::channel('search')->info('✅ ผลลัพธ์จาก Google API', [
                'count' => count($results['results'] ?? []),
                'status' => $results['status'] ?? null,
            ]);
            return response()->json($results);
        }
        catch (\Throwable $e)
        {
            \Log::channel('search')->error('❌ เกิดข้อผิดพลาดในการค้นหา', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'message' => 'เกิดข้อผิดพลาดในการค้นหาร้านอาหาร',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
