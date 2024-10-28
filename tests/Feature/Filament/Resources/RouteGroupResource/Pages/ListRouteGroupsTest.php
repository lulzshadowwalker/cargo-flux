<?php

namespace Tests\Feature\Filament\Resources\RouteGroupResource\Pages;

use App\Filament\Resources\RouteGroupResource;
use App\Filament\Resources\RouteGroupResource\Pages\ListRouteGroups;
use App\Models\RouteGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAdmin;

class ListRouteGroupsTest extends TestCase
{
    use RefreshDatabase, WithAdmin;

    public function test_it_renders_the_page(): void
    {
        $this->get(RouteGroupResource::getUrl('index'))
            ->assertOk();
    }

    public function test_page_contains_route_group_records(): void
    {
        $groups = RouteGroup::factory()->count(5)->create();

        Livewire::test(ListRouteGroups::class)
            ->assertCanSeeTableRecords($groups);
    }
}
