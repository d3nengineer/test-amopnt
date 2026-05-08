<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Iqair\IqairMeasurementListing;
use Illuminate\Http\JsonResponse;

class IqairMeasurementController extends Controller
{
    public function __construct(private readonly IqairMeasurementListing $measurements) {}

    public function index(): JsonResponse
    {
        return response()->json($this->measurements->latest());
    }
}
