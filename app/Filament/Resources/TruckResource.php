<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TruckResource\Pages;
use App\Filament\Resources\TruckResource\RelationManagers;
use App\Models\Truck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TruckResource extends Resource
{
    protected static ?string $model = Truck::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('license_plate')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('driver_id')
                    ->relationship('driver', 'id')
                    ->required(),
                Forms\Components\TextInput::make('truck_category_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('driver')
                    ->label(__('filament/resources/truck-resource.driver'))
                    ->getStateUsing(fn($record) => $record->driver->fullName)
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

                Tables\Columns\TextColumn::make('license_plate')
                    ->label(__('filament/resources/truck-resource.license-plate'))
                    ->badge()
                    ->alignCenter()
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->label(__('filament/resources/truck-resource.category'))
                    ->getStateUsing(fn($record) => $record->category)
                    ->formatStateUsing(fn($state) => "{$state->name} ({$state->tonnage} " . __('filament/resources/truck-resource.tons') . ")")
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('orders')
                    ->label(__('filament/resources/truck-resource.orders'))
                    ->getStateUsing(fn($record) => $record->orders->count())
                    ->badge()
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament/resources/truck-resource.registeration-date'))
                    ->dateTime()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
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
            'index' => Pages\ListTrucks::route('/'),
        ];
    }
}
