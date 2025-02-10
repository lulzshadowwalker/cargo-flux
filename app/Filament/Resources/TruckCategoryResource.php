<?php

namespace App\Filament\Resources;

use App\Filament\Exports\TruckCategoryExporter;
use App\Filament\Resources\TruckCategoryResource\Pages;
use App\Filament\Resources\TruckCategoryResource\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\TruckCategoryResource\RelationManagers\TrucksRelationManager;
use App\Models\TruckCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\Model;

class TruckCategoryResource extends Resource
{
    protected static ?string $model = TruckCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'tonnage',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('filament/resources/truck-category-resource.name') => $record->name,
            __('filament/resources/truck-category-resource.tonnage') => $record
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.fleet-management');
    }

    public static function form(Form $form): Form
    {
        $disabled = $form->getRecord()?->trucks()->exists() ?? false;

        return $form
            ->schema([
                Forms\Components\Section::make(__('filament/resources/truck-category-resource.details'))
                    ->description(__('filament/resources/truck-category-resource.details-description'))
                    ->aside()
                    ->schema([
                        // Forms\Components\SpatieMediaLibraryFileUpload::make('image')

                        Forms\Components\TextInput::make('name')
                            ->helperText(__('filament/resources/truck-category-resource.name-helper-text'))
                            ->placeholder(__('filament/resources/truck-category-resource.name-placeholder'))
                            ->maxLength(255)
                            ->required()
                            ->translatable(),

                        Forms\Components\TextInput::make('tonnage')
                            ->helperText($disabled ? __('filament/resources/truck-category-resource.tonnage-disabled-helper-text') : __('filament/resources/truck-category-resource.tonnage-helper-text'))
                            ->placeholder(__('filament/resources/truck-category-resource.tonnage-placeholder'))
                            ->required()
                            ->numeric()
                            ->suffix(trans_choice('filament/resources/truck-category-resource.ton', 10))
                            ->disabled($disabled)
                            ->minValue(0),

                        Forms\Components\TextInput::make('length')
                            ->helperText($disabled ? __('filament/resources/truck-category-resource.length-disabled-helper-text') : __('filament/resources/truck-category-resource.length-helper-text'))
                            ->placeholder(__('filament/resources/truck-category-resource.length-placeholder'))
                            ->required()
                            ->numeric()
                            ->suffix(trans_choice('filament/resources/truck-category-resource.meter', 10))
                            ->disabled($disabled)
                            ->minValue(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament/resources/truck-category-resource.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tonnage')
                    ->label(__('filament/resources/truck-category-resource.tonnage'))
                    ->formatStateUsing(fn($state) => $state . ' ' . trans_choice('filament/resources/truck-category-resource.ton', $state))
                    ->searchable()
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('length')
                    ->label(__('filament/resources/truck-category-resource.length'))
                    ->formatStateUsing(fn($state) => $state . ' ' . trans_choice('filament/resources/truck-category-resource.meter', $state))
                    ->searchable()
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('trucks')
                    ->label(__('filament/resources/truck-category-resource.trucks'))
                    ->getStateUsing(fn($record) => $record->trucks()->count())
                    ->badge()
                    ->tooltip(__('filament/resources/truck-category-resource.is-available-tooltip'))
                    ->color(fn($state) => $state > 0 ? 'primary' : Color::hex('#9ca3af')),

                Tables\Columns\TextColumn::make('orders')
                    ->label(__('filament/resources/truck-category-resource.orders'))
                    ->getStateUsing(fn($record) => $record->orders()->count())
                    ->badge()
                    ->color(fn($state) => $state > 0 ? 'primary' : Color::hex('#9ca3af'))
                    ->sortable(),

                Tables\Columns\IconColumn::make('isAvailable')
                    ->label(__('filament/resources/truck-category-resource.is-available'))
                    ->boolean()
                    ->getStateUsing(fn($record) => $record->isAvailable)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament/resources/truck-category-resource.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament/resources/truck-category-resource.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(TruckCategoryExporter::class)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->exporter(TruckCategoryExporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TrucksRelationManager::class,
            OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTruckCategories::route('/'),
            'create' => Pages\CreateTruckCategory::route('/create'),
            'edit' => Pages\EditTruckCategory::route('/{record}/edit'),
        ];
    }
}
