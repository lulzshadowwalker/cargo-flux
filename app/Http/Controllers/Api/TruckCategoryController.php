<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TruckCategoryResource;
use App\Models\TruckCategory;

class TruckCategoryController extends Controller
{
    public function index()
    {
        return TruckCategoryResource::collection(TruckCategory::all());
    }

    public function show(string $language, TruckCategory $truckCategory)
    {
        return TruckCategoryResource::make($truckCategory);
    }
}
