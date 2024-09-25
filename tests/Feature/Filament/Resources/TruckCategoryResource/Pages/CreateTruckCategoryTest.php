<?php

namespace Tests\Feature\Filament\Resources\TruckCategoryResource\Pages;

use App\Filament\Resources\TruckCategoryResource;
use App\Filament\Resources\TruckCategoryResource\Pages\CreateTruckCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\WithAdmin;
use Tests\Traits\WithFilamentTranslatableFieldsPlugin;

class CreateTruckCategoryTest extends TestCase
{
    use RefreshDatabase, WithAdmin, WithFilamentTranslatableFieldsPlugin;

    public function test_it_renders_the_page()
    {
        $this->get(TruckCategoryResource::getUrl('create'))->assertOk();
    }

    public function test_page_contains_truck_category_form()
    {
        Livewire::test(CreateTruckCategory::class)
            ->assertSee('Name');
    }

    public function test_it_creates_truck_category()
    {
        Livewire::test(CreateTruckCategory::class)
            ->fillForm([
                'name.en' => 'Truck Category',
                'tonnage' => 10,
                'length' => 10,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('truck_categories', [
            'name' => json_encode(['en' => 'Truck Category']),
            'tonnage' => 10,
            'length' => 10,
        ]);
    }

    #[DataProvider('validationProvider')]
    public function test_validation_errors($input, $output): void
    {
        Livewire::test(CreateTruckCategory::class)
            ->fillForm($input)
            ->call('create')
            ->assertHasErrors($output);
    }

    public static function validationProvider(): array
    {
        return [
            [
                ['name.en' => '', 'tonnage' => 10],
                ['data.name.en' => ['required']],

                ['name.en' => 'Truck Category', 'tonnage' => ''],
                ['data.tonnage' => ['required']],

                ['name.en' => 'Truck Category', 'tonnage' => 'string'],
                ['data.tonnage' => ['numeric']],

                ['name.en' => 'Truck Category', 'tonnage' => -1],
                ['data.tonnage' => ['min']],
            ],
        ];
    }
}

