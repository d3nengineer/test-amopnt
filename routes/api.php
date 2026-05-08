<?php

use App\Http\Controllers\Api\IqairMeasurementController;
use Illuminate\Support\Facades\Route;

Route::get('iqair-measurements', [IqairMeasurementController::class, 'index'])
    ->name('iqair-measurements.index');
