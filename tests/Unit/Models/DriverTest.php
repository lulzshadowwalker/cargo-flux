<?php

namespace Tests\Unit\Models;

use App\Models\Driver;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_out_a_notification_when_a_driver_registers(): void
    {
        Driver::factory()->create();

        Notification::assertNotified();
    }

    public function test_it_updates_the_user_name_when_upadting_the_name_at_the_driver_model(): void
    {
        $driver = Driver::factory()->create();

        $this->assertEquals($driver->user->first_name, $driver->getTranslation('first_name', 'ar'));
        $this->assertEquals($driver->user->last_name, $driver->getTranslation('last_name', 'ar'));

        $driver->setTranslation('first_name', 'ar', 'john');
        $driver->setTranslation('last_name', 'ar', 'doe');

        $driver->refresh();
        $this->assertEquals($driver->user->first_name, $driver->getTranslation('first_name', 'ar'));
        $this->assertEquals($driver->user->last_name, $driver->getTranslation('last_name', 'ar'));
    }
}
