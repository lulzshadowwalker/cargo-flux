<?php

namespace Tests\Feature\Filament\Pages;

use App\Enums\Audience;
use App\Filament\Pages\PushNotification;
use Livewire\Livewire;
use Tests\TestCase;

class PushNotificationTest extends TestCase
{
    public function test_it_sends_a_notification()
    {
        Livewire::test(PushNotification::class)
            ->fillForm([
                'audience' => Audience::CUSTOMERS->value,
                'image' => null,
                'title' => 'Test Title',
                'body' => 'Test Body',
            ])
            ->call('publish')
            ->assertHasNoErrors();
    }
}
