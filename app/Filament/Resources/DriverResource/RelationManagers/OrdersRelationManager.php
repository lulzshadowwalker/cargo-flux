<?php

namespace App\Filament\Resources\DriverResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return OrderResource::form($form);
    }

    public function table(Table $table): Table
    {
        return OrderResource::table(
            $table->recordTitleAttribute('number')
        );
    }
}
