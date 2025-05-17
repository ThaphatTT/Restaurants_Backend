<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RestaurantService
{
    public function searchRestaurants(string $keyword): array
    {
        //นำ req มา hash พร้อมกับเพิ่มหัวข้อเก่อน hash
        $cacheKey ='restaurant_search_'.md5($keyword);

        // ใช้ Laravel Cache เพื่อจดจำผลลัพธ์ของการค้นหาร้านอาหารจาก Google Maps API
        // ถ้าเคยค้นคำนี้มาก่อนในช่วง 10 นาทีที่ผ่านมา → ดึงจาก cache
        // ถ้ายังไม่เคย → ยิง request ไปที่ Google แล้ว cache ผลลัพธ์ไว้ 10 นาที
        return Cache::remember($cacheKey, now()->addMinutes(value: 10), function () use ($keyword) {
            $apiKey = config('services.google.map_api_key');

            $response = Http::get("https://maps.googleapis.com/maps/api/place/textsearch/json", [
                'query' => $keyword,
                'key' => $apiKey,
            ]);

            return $response->json();
        });
    }
}
