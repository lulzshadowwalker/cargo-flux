<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Driver;
use App\Support\SystemActor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrackingRelationManager extends RelationManager
{
    protected static string $relationship = 'tracking';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament/resources/order-resource.status'))
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state->label())
                    ->color(fn($state) => $state->color())
                    ->searchable(),

                Tables\Columns\TextColumn::make('actor')
                    ->label(__('filament/resources/order-resource.actor'))
                    ->getStateUsing(fn($record) => $record->isSystemActor ? __('filament/resources/order-resource.system') : $record->actor->fullName)
                    ->description(function ($record) {
                        if ($record->isCustomer || $record->isDriver) {
                            return $record->actor->phone;
                        }

                        return null;
                    }),

                Tables\Columns\TextColumn::make('actor_type')
                    ->label(__('filament/resources/order-resource.actor-type'))
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        Customer::class => __('filament/resources/order-resource.customer'),
                        Driver::class => __('filament/resources/order-resource.driver'),
                        SystemActor::class => __('filament/resources/order-resource.system'),
                        default => __('filament/resources/order-resource.unknown'),
                    })
                    ->color(fn($state) => match ($state) {
                        Customer::class => 'primary',
                        Driver::class => 'info',
                        SystemActor::class => 'success',
                        default => 'danger',
                    }),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
