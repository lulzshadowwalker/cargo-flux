<?php

namespace App\Observers;

use App\Enums\SupportTicketStatus;
use App\Filament\Resources\SupportTicketResource;
use App\Models\SupportTicket;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class SupportTicketObserver
{
    public function creating(SupportTicket $supportTicket): void
    {
        $supportTicket->number = strtoupper(uniqid('TICKET-'));

        if (! $supportTicket->status) {
            $supportTicket->status = SupportTicketStatus::OPEN;
        }
    }

    public function created(SupportTicket $supportTicket): void
    {
        $admins = User::admins()->get();

        Notification::make()
            ->title(__('notifications.support-ticket-created.title'))
            ->actions([
                Action::make('go-to-ticket')
                    ->button()
                    ->label('View Ticket')
                    ->url(SupportTicketResource::getUrl('edit', ['record' => $supportTicket]))
            ])
            ->icon(SupportTicketResource::getNavigationIcon())
            ->sendToDatabase($admins);
    }
}
