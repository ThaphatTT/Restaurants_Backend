<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RestaurantController;

Route::get('/search', [RestaurantController::class,'search']);
