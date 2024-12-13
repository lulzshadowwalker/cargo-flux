<?php

namespace App\Filament\Pages;

use App\Enums\Audience;
use App\Services\FirebasePushNotification\AudienceNotificationStrategy;
use App\Support\PushNotification as SupportPushNotification;
use Exception;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PushNotification extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static string $view = 'filament.pages.push-notification';

    public array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.notifications');
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament/pages/push-notification.push-notifications');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/pages/push-notification.push-notifications');
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make(__('filament/pages/push-notification.publish-notification'))
                    ->description(__('filament/pages/push-notification.publish-notification-description'))
                    ->aside()
                    ->schema([
                        Forms\Components\Select::make('audience')
                            ->label(__('filament/pages/push-notification.audience'))
                            ->placeholder(__('filament/pages/push-notification.target-audience'))
                            ->options(Arr::sort(Arr::collapse(Arr::map(Audience::cases(), fn($status) => [$status->value => $status->label()]))))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\FileUpload::make('image')
                            ->label(__('filament/pages/push-notification.image'))
                            ->image()
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->storeFiles(false)
                            ->maxSize(4 * 1024 * 1024),

                        Forms\Components\TextInput::make('title')
                            ->label(__('filament/pages/push-notification.title'))
                            ->placeholder(__('filament/pages/push-notification.title-placeholder'))
                            ->required()
                            ->maxLength(36),

                        Forms\Components\TextInput::make('body')
                            ->label(__('filament/pages/push-notification.body'))
                            ->placeholder(__('filament/pages/push-notification.body-placeholder'))
                            ->required()
                            ->maxLength(150),
                    ]),
            ]);
    }

    protected function getActions(): array
    {
        return [
            Action::make(__('filament/pages/push-notification.publish'))
                ->action(fn() => $this->publish()),
        ];
    }

    public function publish(): void
    {
        try {
            $notification = new SupportPushNotification(
                title: $this->data['title'],
                body: $this->data['body'],
            );

            // TODO: use PushNotificationService instead
            (new AudienceNotificationStrategy)->send($notification, $this->data['audience']);

            $this->form->fill();

            Notification::make()
                ->success()
                ->title(__('filament/pages/push-notification.notification-sent'))
                ->send();
        } catch (Exception $e) {
            Log::error('Failed to send push notification', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->danger()
                ->title(__('filament/pages/push-notification.notification-failed'))
                ->send();
        }
    }
}
