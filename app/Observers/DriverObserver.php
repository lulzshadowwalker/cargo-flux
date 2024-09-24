<?php

namespace App\Observers;

use App\Enums\DriverStatus;
use App\Filament\Resources\DriverResource;
use App\Models\Driver;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class DriverObserver
{
    public function creating(Driver $driver): void
    {
        $driver->status = DriverStatus::UNDER_REVIEW;
    }

    public function created(Driver $driver): void
    {
        $admins = User::admins()->get();

        Notification::make()
            ->title(__('notifications.driver-created.title'))
            ->actions([
                Action::make('go-to-driver')
                    ->button()
                    ->label(__('notifications.driver-created.view-driver'))
                    ->url(DriverResource::getUrl('edit', ['record' => $driver]))
            ])
            ->icon(DriverResource::getNavigationIcon())
            ->sendToDatabase($admins);
    }
}
