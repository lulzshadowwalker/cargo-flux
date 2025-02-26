<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\SupportTicketResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    public function form(Form $form): Form
    {
        return SupportTicketResource::form($form);
    }

    public function table(Table $table): Table
    {
        return SupportTicketResource::table($table->recordTitleAttribute('message'));
    }
}
