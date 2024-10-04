<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Language;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Notifications\FakeDatabaseNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $accessToken = $this->user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$accessToken}");
        $this->actingAs($this->user);
    }

    public function test_it_returns_all_notifications()
    {
        FacadesNotification::send($this->user, new FakeDatabaseNotification);
        $resource = NotificationResource::collection($this->user->notifications);
        $request = Request::create(route('notifications.index', ['lang' => Language::EN]), 'GET');

        $this->getJson(route('notifications.index', ['lang' => Language::EN]))
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }

    public function test_it_returns_a_single_notification()
    {
        FacadesNotification::send($this->user, new FakeDatabaseNotification);
        $notification = $this->user->notifications->first();
        $resource = NotificationResource::make($notification);
        $request = Request::create(route('notifications.show', [
            'lang' => Language::EN,
            'notification' => $notification
        ]), 'GET');

        $this->getJson(route('notifications.show', [
            'lang' => Language::EN,
            'notification' => $notification
        ]))
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }

    public function test_it_marks_a_notification_as_read()
    {
        FacadesNotification::send($this->user, new FakeDatabaseNotification);
        $notification = $this->user->notifications->first();

        $readNotification = clone $notification;
        $readNotification->markAsRead();

        $resource = NotificationResource::make($readNotification);
        $request = Request::create(route('notifications.mark-as-read', [
            'lang' => Language::EN,
            'notification' => $notification
        ]), 'PATCH');


        $this->patchJson(route('notifications.mark-as-read', [
            'lang' => Language::EN,
            'notification' => $notification
        ]))
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }

    public function test_it_marks_all_notifications_as_read()
    {
        FacadesNotification::send($this->user, new FakeDatabaseNotification);
        $resource = NotificationResource::collection($this->user->notifications);
        $request = Request::create(route('notifications.mark-all-as-read', ['lang' => Language::EN]), 'PATCH');

        $this->patchJson(route('notifications.mark-all-as-read', ['lang' => Language::EN]))
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                $resource->response($request)->getData(true),
            );
    }

    public function test_it_deletes_a_single_notification()
    {
        FacadesNotification::send($this->user, new FakeDatabaseNotification);
        $notification = $this->user->notifications->first();

        $request = Request::create(route('notifications.destroy.single', [
            'lang' => Language::EN,
            'notification' => $notification
        ]), 'DELETE');

        $this->deleteJson(route('notifications.destroy.single', [
            'lang' => Language::EN,
            'notification' => $notification
        ]))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_it_deletes_all_notifications()
    {
        FacadesNotification::send($this->user, new FakeDatabaseNotification);
        $request = Request::create(route('notifications.destroy.all', ['lang' => Language::EN]), 'DELETE');

        $this->deleteJson(route('notifications.destroy.all', ['lang' => Language::EN]))
            ->assertStatus(Response::HTTP_OK);
    }
}
