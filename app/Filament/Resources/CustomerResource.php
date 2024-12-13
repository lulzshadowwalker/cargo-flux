<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\UserStatus;
use App\Filament\Exports\CustomerExporter;
use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\ReviewsRelationManager;
use App\Models\Customer;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;

class CustomerResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament/resources/customer-resource.customer-information'))
                    ->relationship('user')
                    ->description(__('filament/resources/customer-resource.customer-information-description'))
                    ->aside()
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label(__('filament/resources/customer-resource.first-name'))
                            ->placeholder(__('filament/resources/customer-resource.first-name-placeholder'))
                            ->maxLength(255)
                            ->required(),

                        Forms\Components\TextInput::make('last_name')
                            ->label(__('filament/resources/customer-resource.last-name'))
                            ->placeholder(__('filament/resources/customer-resource.last-name-placeholder'))
                            ->maxLength(255)
                            ->required(),

                        Forms\Components\TextInput::make('phone')
                            ->label(__('filament/resources/customer-resource.phone'))
                            ->placeholder(__('filament/resources/customer-resource.phone-placeholder'))
                            ->maxLength(20)
                            ->disabled()
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->label(__('filament/resources/customer-resource.email'))
                            ->placeholder('email@example.com')
                            ->maxLength(255)
                            ->email(),
                    ]),

                Forms\Components\Section::make(__('filament/resources/customer-resource.company-information'))
                    ->description(__('filament/resources/customer-resource.company-information-description'))
                    ->aside()
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label(__('filament/resources/customer-resource.company-name'))
                            ->maxLength(255)
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.fullName')
                    ->label(__('filament/resources/customer-resource.full-name'))
                    ->searchable(query: function (Builder $query, $search) {
                        $query->whereHas('user', function (Builder $subQuery) use ($search) {
                            $subQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.phone')
                    ->label(__('filament/resources/customer-resource.phone'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('filament/resources/customer-resource.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('upcoming-orders')
                    ->label(__('filament/resources/customer-resource.upcoming-orders'))
                    ->badge()
                    ->color(fn($state) => $state !== 'none' ? $state?->color() : Color::hex('#4b5563'))
                    ->formatStateUsing((fn($state) => $state !== 'none' ? $state->label() :   __('filament/resources/customer-resource.none')))
                    ->getStateUsing(function ($record) {
                        if ($activeOrder = $record->orders()->active()->first()) {
                            return $activeOrder->status;
                        }

                        if ($record->orders()->scheduled()->count() > 0) {
                            return OrderStatus::SCHEDULED;
                        }

                        return 'none';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('orders-count')
                    ->label(__('filament/resources/customer-resource.orders'))
                    ->badge()
                    ->color(fn($state) => $state > 0 ? Color::hex('#10b981') : Color::hex('#4b5563'))
                    ->getStateUsing(fn($record) => $record->orders()->count())
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.status')
                    ->label(__('filament/resources/customer-resource.status'))
                    ->badge()
                    ->color(fn($state) => $state->color())
                    ->formatStateUsing(fn($state) => $state->label())
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament/resources/customer-resource.registered-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament/resources/customer-resource.last-updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(CustomerExporter::class)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('activate')
                        ->label(__('filament/resources/customer-resource.activate'))
                        ->color(UserStatus::ACTIVE->color())
                        ->icon(UserStatus::ACTIVE->icon())
                        ->visible(fn($record) => !$record->user->isActive)
                        ->action(fn($record) => $record->user->activate()),

                    Tables\Actions\Action::make('suspend')
                        ->label(__('filament/resources/customer-resource.suspend'))
                        ->color(UserStatus::SUSPENDED->color())
                        ->icon(UserStatus::SUSPENDED->icon())
                        ->visible(fn($record) => !$record->user->isSuspended)
                        ->action(fn($record) => $record->user->suspend()),

                    Tables\Actions\Action::make('ban')
                        ->label(__('filament/resources/customer-resource.ban'))
                        ->color(UserStatus::BANNED->color())
                        ->icon(UserStatus::BANNED->icon())
                        ->visible(fn($record) => !$record->user->isBanned)
                        ->action(fn($record) => $record->user->ban()),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

                ExportBulkAction::make()
                    ->exporter(CustomerExporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
            ReviewsRelationManager::class,
            // TicketsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'edit' => Pages\EditCustomer::route('/{record}'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'update',
            // 'delete',
            // 'delete_any',
        ];
    }
}
