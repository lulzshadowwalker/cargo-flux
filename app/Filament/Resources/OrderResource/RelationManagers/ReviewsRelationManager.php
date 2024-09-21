<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Resources\ReviewResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Form $form): Form
    {
        return ReviewResource::form($form);
    }

    public function table(Table $table): Table
    {
        return ReviewResource::table(
            $table->recordTitleAttribute('comment')
        );
    }
}
