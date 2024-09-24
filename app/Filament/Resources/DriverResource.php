<?php

namespace App\Filament\Resources;

use App\Enums\DriverStatus;
use App\Enums\OrderStatus;
use App\Enums\UserStatus;
use App\Filament\Resources\DriverResource\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\DriverResource\Pages;
use App\Filament\Resources\DriverResource\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\DriverResource\RelationManagers\TruckRelationManager;
use App\Models\Driver;
use App\Models\TruckCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament/resources/driver-resource.driver-information'))
                    ->description(__('filament/resources/driver-resource.driver-information-description'))
                    ->aside()
                    ->schema([
                        Forms\Components\TextInput::make('user.first_name')
                            ->label(__('filament/resources/driver-resource.first-name'))
                            ->placeholder(__('filament/resources/driver-resource.first-name-placeholder'))
                            ->maxLength(255)
                            ->required(),

                        Forms\Components\TextInput::make('user.last_name')
                            ->label(__('filament/resources/driver-resource.last-name'))
                            ->placeholder(__('filament/resources/driver-resource.last-name-placeholder'))
                            ->maxLength(255)
                            ->required(),

                        Forms\Components\TextInput::make('user.phone')
                            ->label(__('filament/resources/driver-resource.phone'))
                            ->placeholder(__('filament/resources/driver-resource.phone-placeholder'))
                            ->maxLength(20)
                            ->required(),

                        Forms\Components\TextInput::make('user.email')
                            ->label(__('filament/resources/driver-resource.email'))
                            ->placeholder('email@example.com')
                            ->maxLength(255)
                            ->email(),

                        Forms\Components\Select::make('status')
                            ->options(Arr::collapse(Arr::map(DriverStatus::cases(), fn($status) => [$status->value => $status->label()])))
                            ->default(DriverStatus::APPROVED)
                            ->label(__('filament/resources/driver-resource.registeration-status'))
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('iban')
                            ->label(__('filament/resources/driver-resource.iban'))
                            ->placeholder(__('filament/resources/driver-resource.iban-placeholder'))
                            ->maxLength(34),
                    ]),

                Forms\Components\Section::make(__('filament/resources/driver-resource.truck-information'))
                    ->description(__('filament/resources/driver-resource.truck-information-description'))
                    ->aside()
                    ->schema([
                        Forms\Components\TextInput::make('truck.license_plate')
                            ->label(__('filament/resources/driver-resource.license-plate'))
                            ->placeholder(__('filament/resources/driver-resource.license-plate-placeholder'))
                            ->maxLength(255)
                            ->required(),

                        Forms\Components\Select::make('driver.truck.category')
                            ->label(__('filament/resources/driver-resource.truck-category'))
                            ->searchable()
                            ->getSearchResultsUsing(fn(string $search): array => TruckCategory::where('name', 'like', "%{$search}%")->orWhere('tonnage', 'like', "%{$search}%")->get()->map(fn($category) => [$category->id => $category->name . ' (' . $category->tonnage . ' ' . __('filament/resources/driver-resource.tons') . ')'])->toArray())
                            ->preload()
                            ->live()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.fullName')
                    ->label(__('filament/resources/driver-resource.full-name'))
                    ->searchable(query: function (Builder $query, $search) {
                        $query->whereHas('user', function (Builder $subQuery) use ($search) {
                            $subQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.phone')
                    ->label(__('filament/resources/driver-resource.phone'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('filament/resources/driver-resource.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('upcoming-orders')
                    ->label(__('filament/resources/driver-resource.upcoming-orders'))
                    ->badge()
                    ->color(fn($state) => $state !== 'none' ? $state?->color() : Color::hex('#4b5563'))
                    ->formatStateUsing((fn($state) => $state !== 'none' ? $state->label() :   __('filament/resources/driver-resource.none')))
                    ->getStateUsing(function ($record) {
                        if ($record->orders()->inProgress()->count() > 0) {
                            return OrderStatus::IN_PROGRESS;
                        }

                        if ($record->orders()->scheduled()->count() > 0) {
                            return OrderStatus::SCHEDULED;
                        }

                        return 'none';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('orders-count')
                    ->label(__('filament/resources/driver-resource.orders'))
                    ->badge()
                    ->color(fn($state) => $state > 0 ? Color::hex('#10b981') : Color::hex('#4b5563'))
                    ->getStateUsing(fn($record) => $record->orders()->count())
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.status')
                    ->label(__('filament/resources/driver-resource.status'))
                    ->badge()
                    ->color(fn($state) => $state->color())
                    ->formatStateUsing(fn($state) => $state->label())
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament/resources/driver-resource.registeration-status'))
                    ->badge()
                    ->color(fn($state) => $state->color())
                    ->formatStateUsing(fn($state) => $state->label())
                    ->sortable(),

                Tables\Columns\TextColumn::make('iban')
                    ->label(__('filament/resources/driver-resource.iban'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament/resources/driver-resource.registered-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament/resources/driver-resource.last-updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve-registration')
                        ->label(__('filament/resources/driver-resource.approve-registration'))
                        ->color(DriverStatus::APPROVED->color())
                        ->icon(DriverStatus::APPROVED->icon())
                        ->visible(fn($record) => !$record->isApproved)
                        ->action(fn($record) => $record->approve())
                        ->requiresConfirmation(),

                    Tables\Actions\Action::make('reject-registration')
                        ->label(__('filament/resources/driver-resource.reject-registration'))
                        ->color(DriverStatus::REJECTED->color())
                        ->icon(DriverStatus::REJECTED->icon())
                        ->visible(fn($record) => !$record->isRejected)
                        ->action(fn($record) => $record->reject())
                        ->requiresConfirmation(),

                    Tables\Actions\Action::make('activate')
                        ->label(__('filament/resources/driver-resource.activate'))
                        ->color(UserStatus::ACTIVE->color())
                        ->icon(UserStatus::ACTIVE->icon())
                        ->visible(fn($record) => !$record->user->isActive)
                        ->action(fn($record) => $record->user->activate()),

                    Tables\Actions\Action::make('suspend')
                        ->label(__('filament/resources/driver-resource.suspend'))
                        ->color(UserStatus::SUSPENDED->color())
                        ->icon(UserStatus::SUSPENDED->icon())
                        ->visible(fn($record) => !$record->user->isSuspended)
                        ->action(fn($record) => $record->user->suspend())
                        ->requiresConfirmation(),

                    Tables\Actions\Action::make('ban')
                        ->label(__('filament/resources/driver-resource.ban'))
                        ->color(UserStatus::BANNED->color())
                        ->icon(UserStatus::BANNED->icon())
                        ->visible(fn($record) => !$record->user->isBanned)
                        ->action(fn($record) => $record->user->ban())
                        ->requiresConfirmation(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TruckRelationManager::class,
            OrdersRelationManager::class,
            // TicketsRelationManager::class,
            ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDrivers::route('/'),
            'create' => Pages\CreateDriver::route('/create'),
            'edit' => Pages\EditDriver::route('/{record}/edit'),
        ];
    }
}
