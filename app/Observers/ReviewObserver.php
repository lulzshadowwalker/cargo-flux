<?php

namespace App\Observers;

use App\Filament\Resources\ReviewResource;
use App\Models\Review;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class ReviewObserver
{
    public function created(Review $review): void
    {
        $admins = User::admins()->get();

        Notification::make()
            ->title(__('notifications.review-created.title'))
            ->actions([
                Action::make('go-to-review')
                    ->button()
                    ->label(__('notifications.review-created.view-review'))
                    ->url(ReviewResource::getUrl('view', ['record' => $review]))
            ])
            ->icon(ReviewResource::getNavigationIcon())
            ->sendToDatabase($admins);
    }
}
