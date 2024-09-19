<?php

namespace App\Filament\Resources;

use App\Enums\OrderPaymentMethod;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('payment_method')
                    ->required(),
                Forms\Components\TextInput::make('payment_status')
                    ->required(),
                Forms\Components\DateTimePicker::make('scheduled_at'),
                Forms\Components\TextInput::make('pickup_location_latitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('pickup_location_longitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('delivery_location_latitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('delivery_location_longitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('current_location_latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('current_location_longitude')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('current_location_recorded_at'),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'id')
                    ->required(),
                Forms\Components\Select::make('driver_id')
                    ->relationship('driver', 'id')
                    ->required(),
                Forms\Components\Select::make('currency_id')
                    ->relationship('currency', 'id')
                    ->required(),
                Forms\Components\Select::make('truck_id')
                    ->relationship('truck', 'id')
                    ->required(),
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
                    ->description(fn($record) => $record->driver->phone)
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

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('filament/resources/order-resource.amount'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label(__('filament/resources/order-resource.payment-method'))
                    ->badge()
                    ->color(fn($state) => $state->color())
                    ->formatStateUsing(fn($state) => $state->label())
                    ->tooltip(fn($record) => implode(', ', OrderPaymentMethod::labels()))
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label(__('filament/resources/order-resource.payment-status'))
                    ->badge()
                    ->color(fn($state) => $state->color())
                    ->formatStateUsing(fn($state) => $state->label())
                    ->tooltip(fn($record) => implode(', ', OrderPaymentStatus::labels()))
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament/resources/order-resource.status'))
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state->label())
                    ->color(fn($state) => $state->color())
                    ->tooltip(fn($record) => implode(', ', OrderStatus::labels()))
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
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
