<?php

namespace Tests\Unit\Models;

use App\Models\Review;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_notifies_admins_of_new_review_when_created(): void
    {
        User::factory()->admin()->create();

        Review::factory()->create();

        Notification::assertNotified();
    }
}
