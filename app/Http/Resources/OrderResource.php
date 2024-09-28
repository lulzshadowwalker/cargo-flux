<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hasIncludes = $request->has('include');

        return [
            'type' => 'order',
            'id' => $this->id,
            'attributes' => [
                'number' => $this->number,
                'status' => $this->status,
                'paymentMethod' => $this->payment_method,
                'paymentStatus' => $this->payment_status,
                'isScheduled' => isset($this->scheduled_at),
                'scheduledAt' => $this->scheduled_at,
                'cargo' => $this->cargo,
                'currentLocation' => [
                    'latitude' => $this->currentLocation?->latitude,
                    'longitude' => $this->currentLocation?->longitude,
                    'recordedAt' => $this->current_location_recorded_at,
                ],
                'pickupLocation' => [
                    'latitude' => $this->pickupLocation->latitude,
                    'longitude' => $this->pickupLocation->longitude,
                ],
                'deliveryLocation' => [
                    'latitude' => $this->deliveryLocation->latitude,
                    'longitude' => $this->deliveryLocation->longitude,
                ],
                'price' => [
                    'amount' => $this->price->getAmount(),
                    'currency' => $this->price->getCurrency()->getCurrencyCode(),
                ],
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'links' => [
                'self' => route('orders.show', [
                    'lang' => app()->getLocale(),
                    'order' => $this,
                ]),
            ],
            'relationships' => (object) [],
            'includes' => !$hasIncludes ? (object) [] : [
                'customer' => new CustomerResource($this->whenLoaded('customer')),
                'driver' => new DriverResource($this->whenLoaded('driver')),
                'truck' => new TruckResource($this->whenLoaded('truck')),
                'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
                'truckCategory' => new TruckCategoryResource($this->whenLoaded('truckCategory')),
                'tracking' => OrderTrackingEntryResource::collection($this->whenLoaded('tracking')),
                'stages' => $this->mergeWhen($this->includes('stages'), OrderStagesResource::collection($this->stages)),
            ],
        ];
    }

    protected function includes($relationship): bool
    {
        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }
}
