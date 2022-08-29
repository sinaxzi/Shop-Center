<?php

use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ManagerAuthController;
use App\Http\Controllers\ShoppingCenterController;
use Illuminate\Support\Facades\Route;

Route::prefix('map')->group(function (){
    Route::get('route',[LocationController::class,'routing']);
    Route::get('route_in_polygon',[LocationController::class,'routeInPolygon']);
    Route::get('matching',[LocationController::class,"matchingPoints"]);
    Route::get('reverse_shop',[ShoppingCenterController::class,'shopAddresses']);
    Route::get('geo_coding',[ShoppingCenterController::class,'addressToGeo']);
    Route::get('reverse_geo_coding',[ShoppingCenterController::class,'geoToAddress']);
});

Route::prefix('customer')->group(function(){
    // Auth
    Route::prefix('auth')->group(function (){
        Route::post('register',[CustomerAuthController::class,'register'])->name('customer.register');
        Route::post('login',[CustomerAuthController::class,'login'])->name('customer.login');
        Route::post('logout',[CustomerAuthController::class,'logout'])->middleware('auth:sanctum')->name('customer.logout');
    });
});

Route::prefix('shops')->group(function (){
    Route::get('',[ShoppingCenterController::class,"index"]);
    Route::post('nearest',[ShoppingCenterController::class,"nearestShop"])->middleware(['auth:sanctum','ability:shop:nearest']);
    Route::post('store',[ShoppingCenterController::class,"store"])->middleware(['auth:sanctum','ability:shop:create']);
    Route::patch('update/{shoppingCenter}',[ShoppingCenterController::class,'update'])->middleware(['auth:sanctum','ability:shop:update'])->can('update','shoppingCenter');
});

Route::prefix('manager')->group(function(){
    // Auth
    Route::prefix('auth')->group(function (){
        Route::post('register',[ManagerAuthController::class,'register'])->name('manager.register');
        Route::post('login',[ManagerAuthController::class,'login'])->name('manager.login');
        Route::post('logout',[ManagerAuthController::class,'logout'])->middleware('auth:sanctum')->name('manager.logout');
    });
});

Route::post('/image/create',[ImageController::class,'store'])->middleware(['auth:sanctum','ability:image:create']);





