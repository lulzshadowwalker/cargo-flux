<?php

namespace App\Observers;

use App\Enums\UserType;
use App\Filament\Resources\SupportTicketResource;
use App\Models\SupportTicket;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class SupportTicketObserver
{
    public function created(SupportTicket $supportTicket): void
    {
        $admins = User::whereType(UserType::ADMIN)->get();

        Notification::make()
            ->title(__('notifications.support-ticket-created.title'))
            ->actions([
                Action::make('go-to-ticket')
                    ->url(SupportTicketResource::getUrl('index'))
            ])
            ->sendToDatabase($admins);
    }

    public function updated(SupportTicket $supportTicket): void
    {
        //
    }

    public function deleted(SupportTicket $supportTicket): void
    {
        //
    }

    public function restored(SupportTicket $supportTicket): void
    {
        //
    }

    public function forceDeleted(SupportTicket $supportTicket): void
    {
        //
    }
}
