<?php

namespace App\Observers;

use App\Enums\DriverStatus;
use App\Filament\Resources\DriverResource;
use App\Models\Driver;
use App\Events\DriverStatusUpdated;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class DriverObserver
{
    public function creating(Driver $driver): void
    {
        if ($driver->status === null) {
            $driver->status = DriverStatus::UNDER_REVIEW;
        }

        $driver->user->update([
            "first_name" => $driver->getTranslation("first_name", "ar"),
            "last_name" => $driver->getTranslation("last_name", "ar"),
        ]);
    }

    public function created(Driver $driver): void
    {
        $admins = User::admins()->get();

        Notification::make()
            ->title(__("notifications.driver-created.title"))
            ->actions([
                Action::make("go-to-driver")
                    ->button()
                    ->label(__("notifications.driver-created.view-driver"))
                    ->url(
                        DriverResource::getUrl("edit", ["record" => $driver])
                    ),
            ])
            ->icon(DriverResource::getNavigationIcon())
            ->sendToDatabase($admins);
    }

    public function updating(Driver $driver): void
    {
        if ($driver->isDirty("first_name.ar")) {
            $driver->user->update([
                "first_name" => $driver->getTranslation("first_name", "ar"),
            ]);
        }

        if ($driver->isDirty("last_name.ar")) {
            $driver->user->update([
                "last_name" => $driver->getTranslation("last_name", "ar"),
            ]);
        }
    }

    public function updated(Driver $driver): void
    {
        $admins = User::admins()->get();

        if ($driver->isDirty("status")) {
            DriverStatusUpdated::dispatch($driver);
        }
    }
}
