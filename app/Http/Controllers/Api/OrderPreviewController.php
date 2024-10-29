<?php

namespace App\Http\Controllers\Api;

use App\Actions\CalculateOrderPrice;
use App\Contracts\ResponseBuilder;
use App\Exceptions\UnsupportedRouteException;
use App\Http\Requests\OrderPreviewRequest;
use App\Http\Resources\PriceReviewResource;

class OrderPreviewController extends ApiController
{
    public function __construct(
        protected CalculateOrderPrice $calculateOrderPrice,
        protected ResponseBuilder $response,
    )
    {
        //
    }

    public function index(OrderPreviewRequest $request)
    {
        try {
        $price = $this->calculateOrderPrice->handle(
            $request->pickupLocation(),
            $request->deliveryLocation(),
            $request->truckCategory(),
        );

        return PriceReviewResource::make([])->price($price);
        } catch (UnsupportedRouteException $e) {
            return $this->response->error(
                title: 'Unsupported Route',
                detail: 'We do not support this route',
                code: 400,
                indicator: 'UNSUPPORTED_ROUTE'
            )->build();
        }
    }
}
