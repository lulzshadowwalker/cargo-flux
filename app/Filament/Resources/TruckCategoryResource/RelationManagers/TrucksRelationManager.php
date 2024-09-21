<?php

namespace App\Filament\Resources\TruckCategoryResource\RelationManagers;

use App\Filament\Resources\TruckResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TrucksRelationManager extends RelationManager
{
    protected static string $relationship = 'trucks';

    public function form(Form $form): Form
    {
        return TruckResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TruckResource::table(
            $table
                ->recordTitleAttribute('license_plate')
        );
    }
}
