<?php

namespace App\Filament\Resources\ReviewResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class OrderRelationManager extends RelationManager
{
    protected static string $relationship = 'order';

    public function form(Form $form): Form
    {
        return OrderResource::form($form);
    }

    public function table(Table $table): Table
    {
        return OrderResource::table($table->recordTitleAttribute('number'));
    }
}
