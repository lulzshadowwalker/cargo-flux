<?php

namespace App\Filament\Resources;

use Altwaireb\World\Models\State;
use App\Filament\Resources\RouteGroupResource\Pages;
use App\Models\Currency;
use App\Models\RouteGroup;
use App\Models\TruckCategory;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Arr;

class RouteGroupResource extends Resource
{
    protected static ?string $model = RouteGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function getLabel(): ?string
    {
        return 'Routes';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament/resources/route-group-resource.route-information'))
                    ->description(__('filament/resources/route-group-resource.route-information-description'))
                    ->aside()
                    ->schema([
                        Forms\Components\Select::make('pickup_state_id')
                            ->relationship('pickupState', 'name')
                            ->label(__('filament/resources/route-group-resource.pickup-state'))
                            ->placeholder(__('filament/resources/route-group-resource.pickup-state-placeholder'))
                            ->helperText(__('filament/resources/route-group-resource.pickup-state-helper-text'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        //  FIXME: Everything seems to be working fine when editing a route except for destinations
                        Forms\Components\Select::make('destinations')
                            ->label(__('filament/resources/route-group-resource.destinations'))
                            ->placeholder(__('filament/resources/route-group-resource.destinations-placeholder'))
                            ->helperText(__('filament/resources/route-group-resource.destinations-helper-text'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship('states', 'name')
                            ->options(State::orderBy('name')->get()->pluck('name', 'id'))
                            ->required(),
                    ]),

                //  TODO: Try removing the custom create method because this seems like it should work out of the box

                Forms\Components\Section::make(__('filament/resources/route-group-resource.truck-option-pricing'))
                    ->description(__('filament/resources/route-group-resource.route-information-description'))
                    ->aside()
                    ->schema([
                        Forms\Components\Repeater::make('truck_options')
                            ->required()
                            ->relationship('truckOptions')
                            ->schema([
                                Forms\Components\Select::make('truck_category_id')
                                    ->label(__('filament/resources/route-group-resource.truck-option'))
                                    ->placeholder(__('filament/resources/route-group-resource.truck-option-placeholder'))
                                    ->helperText(__('filament/resources/route-group-resource.truck-option-helper-text'))
                                    ->searchable()
                                    ->preload()
                                    ->options(TruckCategory::orderBy('name')->get()->pluck('name', 'id'))
                                    ->required(),

                                Forms\Components\Fieldset::make()
                                    ->label(__('filament/resources/route-group-resource.price'))
                                    ->schema([
                                        Forms\Components\TextInput::make('amount')
                                            ->label(__('filament/resources/route-group-resource.amount'))
                                            ->numeric()
                                            ->rule('min', 0)
                                            ->placeholder('1000')
                                            ->required(),

                                        Forms\Components\Select::make('currency_id')
                                            ->label(__('filament/resources/route-group-resource.currency'))
                                            ->options(Currency::orderBy('code')->get()->pluck('code', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->default(fn() => Currency::where('code', 'SAR')->first()?->id)
                                            ->required(),
                                    ]),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pickupState.name')
                    ->label(__('filament/resources/route-group-resource.pickup-state'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('destinations')
                    ->label(__('filament/resources/route-group-resource.destinations'))
                    ->getStateUsing(fn($record) => Arr::join($record->destinations->pluck('state.name')->toArray(), ', '))
                    ->limit(60)
                    ->searchable(
                        query: fn(Builder $query, $search) =>
                        $query->whereHas('destinations.state', fn(Builder $query) =>
                        $query->where('name', 'like', "%$search%"))
                    ),

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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRouteGroups::route('/'),
            'create' => Pages\CreateRouteGroup::route('/create'),
            'edit' => Pages\EditRouteGroup::route('/{record}/edit'),
        ];
    }
}
