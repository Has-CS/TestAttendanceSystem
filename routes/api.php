<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceDataController;


// s

// Route::middleware('check.api.key')->post('/zkteco/push', [DeviceDataController::class, 'receivePush']);


// Route::middleware('check.apikey')->group(function () {
//     Route::post('/attendance', [AttendanceController::class, 'store']);
// });



Route::middleware('check.apikey')->group(function () {
    Route::post('/device/push', [DeviceDataController::class, 'receivePush']);
});

// Route::post('/device/push', [DeviceDataController::class, 'receivePush']);
