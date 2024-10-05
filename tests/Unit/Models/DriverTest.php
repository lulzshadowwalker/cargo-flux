<?php

namespace Tests\Unit\Models;

use App\Models\Driver;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_out_a_notification_when_a_driver_registers()
    {
        Driver::factory()->create();

        Notification::assertNotified();
    }
}
