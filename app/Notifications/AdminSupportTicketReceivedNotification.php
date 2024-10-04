<?php

namespace App\Notifications;

use App\Filament\Resources\SupportTicketResource;
use App\Models\SupportTicket;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminSupportTicketReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public SupportTicket $supportTicket)
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
            ->line(__('notifications.support-ticket-created.title'))
            ->action(__('notifications.support-ticket-created.view-ticket'), SupportTicketResource::getUrl('edit', ['record' => $this->supportTicket]));
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title(__('notifications.support-ticket-created.title'))
            ->actions([
                Action::make('go-to-ticket')
                    ->button()
                    ->label(__('notifications.support-ticket-created.view-ticket'))
                    ->url(SupportTicketResource::getUrl('edit', ['record' => $this->supportTicket]))
            ])
            ->icon(SupportTicketResource::getNavigationIcon())
            ->getDatabaseMessage();
    }
}
