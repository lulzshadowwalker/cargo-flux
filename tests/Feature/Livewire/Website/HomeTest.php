<?php

namespace Tests\Feature\Livewire\Website;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_renders_the_page(): void
    {
        $this->get(route('website.home'))
            ->assertOk();
    }
}
