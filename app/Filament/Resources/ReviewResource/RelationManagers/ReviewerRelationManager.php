<?php

namespace App\Filament\Resources\ReviewResource\RelationManagers;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\DriverResource;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ReviewerRelationManager extends RelationManager
{
    protected static string $relationship = 'reviewer';

    public function form(Form $form): Form
    {
        if ($this->getOwnerRecord()->reviewer_type === Customer::class) {
            return CustomerResource::form($form);
        }

        return DriverResource::form($form);
    }

    public function table(Table $table): Table
    {
        if ($this->getOwnerRecord()->reviewer_type === Customer::class) {
            return CustomerResource::table($table->recordTitleAttribute('fullName'));
        }

        return DriverResource::table($table->recordTitleAttribute('fullName'));
    }
}
