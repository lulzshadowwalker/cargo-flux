<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_logs_out()
    {
        $user = User::factory()->create();
        $token = $user->createToken(config('app.name'))->plainTextToken;

        $route =  route('auth.logout', [ 'lang' => Language::EN ]);
        $headers = [ 'Authorization' => "Bearer $token" ];
        $this->post($route, [], $headers)->assertOk();
    }
}
