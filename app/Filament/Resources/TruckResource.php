<?php

namespace App\Filament\Resources;

use App\Filament\Exports\TruckExporter;
use App\Filament\Resources\TruckResource\Pages;
use App\Filament\Resources\TruckResource\RelationManagers\CategoryRelationManager;
use App\Filament\Resources\TruckResource\RelationManagers\DriverRelationManager;
use App\Filament\Resources\TruckResource\RelationManagers\OrdersRelationManager;
use App\Models\Truck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;

class TruckResource extends Resource
{
    protected static ?string $model = Truck::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.fleet-management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament/resources/truck-resource.truck-information'))
                    ->aside()
                    ->schema([
                        Forms\Components\TextInput::make('license_plate')
                            ->label(__('filament/resources/truck-resource.license-plate'))
                            ->required()
                            ->maxLength(255),
                    ])
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
            ->headerActions([
                ExportAction::make()
                    ->exporter(TruckExporter::class)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->exporter(TruckExporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DriverRelationManager::class,
            OrdersRelationManager::class,
            CategoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrucks::route('/'),
            'edit' => Pages\EditTruck::route('/{record}/edit'),
        ];
    }
}
