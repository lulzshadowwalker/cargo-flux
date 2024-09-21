<?php

namespace App\Filament\Resources\TruckResource\RelationManagers;

use App\Filament\Resources\TruckCategoryResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CategoryRelationManager extends RelationManager
{
    protected static string $relationship = 'category';

    public function form(Form $form): Form
    {
        return TruckCategoryResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TruckCategoryResource::table(
            $table->recordTitleAttribute('name')
        );
    }
}
