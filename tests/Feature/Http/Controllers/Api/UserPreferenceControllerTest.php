<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Enums\Language;
use App\Http\Resources\UserPreferenceResource;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserPreferenceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_user_preferences()
    {
        $user = User::factory()
            ->has(UserPreference::factory(), 'preferences')
            ->create();
        $resource = UserPreferenceResource::make($user->preferences);
        $request = Request::create(route('profile.preferences.index', ['lang' => Language::EN]), 'GET');
        $this->actingAs($user);

        $this->getJson(route('profile.preferences.index', ['lang' => Language::EN]))
            ->assertOk()
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }

    public function test_it_updates_preferences()
    {
        $user = User::factory()
            ->has(UserPreference::factory(), 'preferences')
            ->create();

        $this->actingAs($user);

        $this->assertEquals($user->preferences->language, 'en');
        $this->patchJson(route('profile.preferences.update', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'language' => 'ar',
                ]
            ]
        ])->assertOk();

        $user->preferences->refresh();
        $this->assertEquals('ar', $user->preferences->language);
    }
}
