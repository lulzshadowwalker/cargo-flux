<?php

namespace App\Filament\Resources\DriverResource\RelationManagers;

use App\Filament\Resources\SupportTicketResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    public function form(Form $form): Form
    {
        return SupportTicketResource::form($form);
    }

    public function table(Table $table): Table
    {
        // FIXME: SQLSTATE[42S22]: Column not found: 1054 Unknown column 'users.driver_id' in 'where clause' (Connection: mysql, SQL: select count(*) as aggregate from `support_tickets` inner join `users` on `users`.`id` = `support_tickets`.`user_id` where `users`.`driver_id` = 1)
        return SupportTicketResource::table($table);
    }
}
