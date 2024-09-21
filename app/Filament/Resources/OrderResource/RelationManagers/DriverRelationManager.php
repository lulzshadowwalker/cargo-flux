<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Resources\DriverResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class DriverRelationManager extends RelationManager
{
    protected static string $relationship = 'driver';

    public function form(Form $form): Form
    {
        return DriverResource::form($form);
    }

    public function table(Table $table): Table
    {
        return DriverResource::table(
            $table
                ->recordTitleAttribute('fullName')
        );
    }
}
