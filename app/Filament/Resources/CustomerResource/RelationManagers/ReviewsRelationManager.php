<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

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
        return ReviewResource::table(
            // FIXME: SQLSTATE[42S22]: Column not found: 1054 Unknown column 'users.customer_id' in 'where clause' (Connection: mysql, SQL: select count(*) as aggregate from `support_tickets` inner join `users` on `users`.`id` = `support_tickets`.`user_id` where `users`.`customer_id` = 1)
            $table->recordTitleAttribute('comment')
        );
    }
}
