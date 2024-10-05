<?php

namespace App\Notifications;

use App\Filament\Resources\DriverResource;
use App\Models\Driver;
use Filament\Notifications\Actions\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class AdminPendingDriverRegisterationRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Driver $driver)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line(__('notifications.admin-pending-driver-registeration-request.title', [
                'name' => $this->driver->fullName,
                'since' => $this->driver->created_at->diffForHumans(),
            ]))
            ->action(
                __('notifications.admin-pending-driver-registeration-request.view-driver'),
                DriverResource::getUrl('edit', ['record' => $this->driver]),
            );
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title(__('notifications.admin-pending-driver-registeration-request.title', [
                'name' => $this->driver->fullName,
                'since' => $this->driver->created_at->diffForHumans(),
            ]))
            ->actions([
                Action::make('go-to-driver')
                    ->button()
                    ->label(__('notifications.admin-pending-driver-registeration-request.view-driver'))
                    ->url(DriverResource::getUrl('edit', ['record' => $this->driver])),
            ])
            ->icon(DriverResource::getNavigationIcon())
            ->getDatabaseMessage();
    }
}
