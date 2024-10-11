<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrderPreviewRequest;
use App\Http\Resources\PriceReviewResource;

class OrderPreviewController extends ApiController
{
    public function index(OrderPreviewRequest $request)
    {
        return PriceReviewResource::make([]);
    }
}
