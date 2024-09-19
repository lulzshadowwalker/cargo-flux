<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Language;
use App\Http\Resources\TruckCategoryResource;
use App\Models\TruckCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class TruckCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_truck_categories()
    {
        $truckCategories = TruckCategory::factory()->count(3)->create();
        $resource = TruckCategoryResource::collection($truckCategories);
        $request = Request::create(route('trucks.categories.index', ['lang' => Language::EN]), 'GET');

        $this->getJson(route('trucks.categories.index', ['lang' => Language::EN]))
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }

    public function test_it_can_show_a_truck_category()
    {
        $truckCategory = TruckCategory::factory()->create();
        $resource = TruckCategoryResource::make($truckCategory);
        $request = Request::create(route('trucks.categories.show', ['lang' => Language::EN, 'truckCategory' => $truckCategory]), 'GET');

        $this->getJson(route('trucks.categories.show', ['lang' => Language::EN, 'truckCategory' => $truckCategory]))
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }
}
