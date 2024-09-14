<?php

namespace Tests\Feature\Filament\dashboard\Resources\ItemResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAdmin;

class ListCustomersTest extends TestCase
{
    use RefreshDatabase, WithAdmin;

    public function test_it_renders_the_page()
    {
        $this->get(CustomerResource::getUrl('index'))->assertOk();
    }

    public function test_page_contains_customer_records()
    {
        $items = Customer::factory()->count(5)->create();
        Livewire::test(ListCustomers::class)
            ->assertCanSeeTableRecords($items);
    }
}
