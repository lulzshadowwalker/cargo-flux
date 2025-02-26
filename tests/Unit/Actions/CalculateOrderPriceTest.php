<?php

namespace Tests\Unit\Actions;

use App\Actions\CalculateOrderPrice;
use App\Exceptions\UnsupportedRouteException;
use App\Models\Currency;
use App\Models\RouteGroup;
use App\Models\TruckCategory;
use App\Services\GeoapifyReverseGeocoder;
use App\Support\GeoPoint;
use Brick\Money\Money;
use Database\Factories\StateFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

//  WARNING: This is an expensive test as it makes real API requests to the reverse geocoder service
class CalculateOrderPriceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_calculate_order_price(): void
    {
        $this->markTestSkipped('CalculateOrderPrice is being rewritten with the process being entirely different');

        // Setup
        //  TODO: Should account for the actual naming from the reverse geocoder and our states table
        $pickupState = StateFactory::new()->create(['name' => 'Riyadh Region']);
        $destinationState = StateFactory::new()->create(['name' => 'Amman']);
        $truckCategory = TruckCategory::factory()->create();
        $currency = Currency::factory()->create(['code' => 'USD']);

        $route = RouteGroup::factory()->create(['pickup_state_id' => $pickupState->id]);
        $route->destinations()->create(['delivery_state_id' => $destinationState->id]);
        $route->truckOptions()->create([
            'truck_category_id' => $truckCategory->id,
            'amount' => 100,
            'currency_id' => $currency->id,
        ]);

        // Act
        //  TODO: Use an interface instead of GeoapifyReverseGeocoder
        $price = (new CalculateOrderPrice(new GeoapifyReverseGeocoder))->handle(
            $ryadh = new GeoPoint(24.7136, 46.6753),
            $amman = new GeoPoint(31.9454, 35.9284),
            $truckCategory,
        );

        // Assert
        $this->assertInstanceOf(Money::class, $price);
        $this->assertEquals(100, $price->getAmount()->toInt());
        $this->assertEquals('USD', $price->getCurrency()->getCurrencyCode());
    }

    public function test_it_throws_an_exception_of_type_unsupported_route_exception_when_route_is_not_supported(): void
    {
        $this->markTestSkipped('CalculateOrderPrice is being rewritten with the process being entirely different');
        $this->expectException(UnsupportedRouteException::class);

        (new CalculateOrderPrice(new GeoapifyReverseGeocoder))->handle(
            $ryadh = new GeoPoint(24.7136, 46.6753),
            $amman = new GeoPoint(31.9454, 35.9284),
            TruckCategory::factory()->create(),
        );
    }
}
