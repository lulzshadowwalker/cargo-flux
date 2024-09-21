<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Resources\CustomerResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CustomerRelationManager extends RelationManager
{
    protected static string $relationship = 'customer';

    public function form(Form $form): Form
    {
        return CustomerResource::form($form);
    }

    public function table(Table $table): Table
    {
        return CustomerResource::table(
            $table
                ->recordTitleAttribute('fullName')
        );
    }
}
