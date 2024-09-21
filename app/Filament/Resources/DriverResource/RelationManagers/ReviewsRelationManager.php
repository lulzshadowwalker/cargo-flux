<?php

namespace App\Filament\Resources\DriverResource\RelationManagers;

use App\Filament\Resources\ReviewResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Form $form): Form
    {
        return ReviewResource::form($form);
    }

    public function table(Table $table): Table
    {
        return ReviewResource::table($table);
    }
}
