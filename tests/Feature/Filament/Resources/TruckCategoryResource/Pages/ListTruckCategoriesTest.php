<?php

namespace Tests\Feature\Filament\Resources\TruckCategoryResource\Pages;

use App\Filament\Resources\TruckCategoryResource;
use App\Filament\Resources\TruckCategoryResource\Pages\ListTruckCategories;
use App\Models\TruckCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAdmin;

class ListTruckCategoriesTest extends TestCase
{
    use RefreshDatabase, WithAdmin;

    public function test_it_renders_the_page()
    {
        $this->get(TruckCategoryResource::getUrl('index'))->assertOk();
    }

    public function test_page_contains_truck_category_records()
    {
        $items = TruckCategory::factory()->count(5)->create();

        Livewire::test(ListTruckCategories::class)
            ->assertCanSeeTableRecords($items);
    }

    public function test_records_have_an_edit_action()
    {
        TruckCategory::factory()->create();

        Livewire::test(ListTruckCategories::class)
            ->assertSeeText('Edit');
    }

    public function test_page_contains_create_action()
    {
        Livewire::test(ListTruckCategories::class)
            ->assertSeeText('Create');
    }
}
