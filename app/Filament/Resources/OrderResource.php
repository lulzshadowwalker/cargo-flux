<?php

namespace App\Filament\Resources;

use App\Enums\OrderPaymentMethod;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Filament\Exports\OrderExporter;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\CustomerRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\DriverRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\TrackingRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\TruckCategoryRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\TruckRelationManager;
use App\Models\Order;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\Model;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/order-resource.orders');
    }

    public static function getLabel(): string
    {
        return __('filament/resources/order-resource.orders');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filament/resources/order-resource.order');
    }

    public static function getgloballysearchableattributes(): array
    {
        return [
            'number',
            'cargo',
            'customer.user.first_name',
            'customer.user.last_name',
            'customer.user.phone',
            'driver.user.first_name',
            'driver.user.last_name',
            'driver.user.phone',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('filament/resources/order-resource.number') => Str::replace('ORDER-', '', $record->number),
            __('filament/resources/order-resource.cargo') => $record->cargo,
            __('filament/resources/order-resource.customer') => $record->customer->fullName,
            __('filament/resources/order-resource.driver') => $record->driver->fullName,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.operations');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament/resources/order-resource.order-information'))
                    ->aside()
                    ->description(__('filament/resources/order-resource.order-information-description'))
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->label(__('filament/resources/order-resource.number'))
                            ->required()
                            ->disabled()
                            ->formatStateUsing(fn($state) => Str::replace('ORDER-', '', $state))
                            ->maxLength(255),

                        Forms\Components\TextInput::make('cargo')
                            ->label(__('filament/resources/order-resource.cargo'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('status')
                            ->label(__('filament/resources/order-resource.status'))
                            ->options(Arr::collapse(Arr::map(OrderStatus::cases(), fn($status) => [$status->value => $status->label()])))
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->label(__('filament/resources/order-resource.amount'))
                            ->required()
                            ->disabled()
                            ->numeric()
                            ->prefix(fn($record) => $record->currency->symbol),

                        Forms\Components\Select::make('payment_method')
                            ->label(__('filament/resources/order-resource.payment-method'))
                            ->options(Arr::collapse(Arr::map(OrderPaymentMethod::cases(), fn($status) => [$status->value => $status->label()])))
                            ->searchable()
                            ->disabled()
                            ->required(),

                        Forms\Components\Select::make('payment_status')
                            ->label(__('filament/resources/order-resource.payment-status'))
                            ->options(Arr::collapse(Arr::map(OrderPaymentStatus::cases(), fn($status) => [$status->value => $status->label()])))
                            ->searchable()
                            ->disabled()
                            ->required(),

                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label(__('filament/resources/order-resource.scheduled-at'))
                            ->native(false)
                            ->suffixAction(
                                Action::make('unschedule-order')
                                    ->icon('heroicon-o-x-mark')
                                    ->requiresConfirmation()
                                    ->action(function (Set $set, $state) {
                                        $set('scheduled_at', null);
                                    })
                            ),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label(__('filament/resources/order-resource.number'))
                    ->formatStateUsing(fn($state) => Str::replace('ORDER-', '', $state))
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer')
                    ->label(__('filament/resources/order-resource.customer'))
                    ->getStateUsing(fn($record) => $record->customer)
                    ->formatStateUsing(fn($state) => $state->fullName)
                    ->description(fn($record) => $record->customer->phone)
                    ->searchable(
                        query: function (Builder $query, $search) {
                            $query->whereHas('customer.user', function (Builder $query) use ($search) {
                                $query->where(function (Builder $query) use ($search) {
                                    $query->where('first_name', 'like', "%{$search}%")
                                        ->orWhere('last_name', 'like', "%{$search}%")
                                        ->orWhere('phone', 'like', "%{$search}%");
                                });
                            });
                        }
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('driver')
                    ->label(__('filament/resources/order-resource.driver'))
                    ->getStateUsing(fn($record) => $record->driver)
                    ->formatStateUsing(fn($state) => $state->fullName)
                    ->description(fn($record) => $record->driver?->phone)
                    ->searchable(
                        query: function (Builder $query, $search) {
                            $query->whereHas('driver.user', function (Builder $query) use ($search) {
                                $query->where(function (Builder $query) use ($search) {
                                    $query->where('first_name', 'like', "%{$search}%")
                                        ->orWhere('last_name', 'like', "%{$search}%")
                                        ->orWhere('phone', 'like', "%{$search}%");
                                });
                            });
                        }
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('cargo')
                    ->label(__('filament/resources/order-resource.cargo'))
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('filament/resources/order-resource.amount'))
                    ->money(currency: fn($record) => $record->currency->code)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label(__('filament/resources/order-resource.payment-method'))
                    ->badge()
                    ->color(fn($state) => $state->color())
                    ->formatStateUsing(fn($state) => $state->label())
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label(__('filament/resources/order-resource.payment-status'))
                    ->badge()
                    ->color(fn($state) => $state->color())
                    ->formatStateUsing(fn($state) => $state->label())
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament/resources/order-resource.status'))
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state->label())
                    ->color(fn($state) => $state->color())
                    ->searchable(),

                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('filament/resources/order-resource.scheduled-at'))
                    ->dateTime()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state === 'Unscheduled' ?  __('filament/resources/order-resource.unscheduled') : $state->diffForHumans())
                    ->getStateUsing(fn($record) => $record->scheduled_at ?? 'Unscheduled')
                    ->color(fn($state) => $state === 'Unscheduled' ? Color::hex('#9ca3af') : null)
                    ->description(fn($record) => $record->scheduled_at?->format('Y-m-d H:i:s') ?? ''),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament/resources/order-resource.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament/resources/order-resource.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(OrderExporter::class)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

                ExportBulkAction::make()
                    ->exporter(OrderExporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CustomerRelationManager::class,
            DriverRelationManager::class,
            TruckRelationManager::class,
            TrackingRelationManager::class,
            ReviewsRelationManager::class,
            TruckCategoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
