<?php

namespace App\Filament\Resources\TruckCategoryResource\Pages;

use App\Filament\Resources\TruckCategoryResource;
use App\Models\TruckCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAdmin;
use Tests\Traits\WithFilamentTranslatableFieldsPlugin;

class EditTruckCategoryTest extends TestCase
{
    use RefreshDatabase, WithAdmin, WithFilamentTranslatableFieldsPlugin;

    protected TruckCategory $category;

    public function setUp(): void
    {
        parent::setUp();

        $this->category = TruckCategory::factory()->create();
    }

    public function test_it_renders_the_page()
    {
        $this->get(TruckCategoryResource::getUrl('edit', [
            'record' => $this->category,
        ]))->assertOk();
    }

    public function test_form_is_prefilled_with_category_data()
    {
        Livewire::test(EditTruckCategory::class, [
            'record' => $this->category->getKey(),
        ])->assertFormSet([
            'name.en' => $this->category->name,
            'tonnage' => $this->category->tonnage,
        ]);
    }

    public function test_it_updates_category()
    {
        $new = TruckCategory::factory()->make();

        Livewire::test(EditTruckCategory::class, [
            'record' => $this->category->getKey(),
        ])
            ->fillForm($new->toArray())
            ->call('save')
            ->assertHasNoFormErrors();

        $this->category->refresh();
        $this->assertEquals($new->name, $this->category->name);

        // Tonnage is disabled if there are trucks associated with the category
        $this->assertEquals($new->tonnage, $this->category->tonnage);
    }
}
