<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers\OrderRelationManager;
use App\Filament\Resources\ReviewResource\RelationManagers\ReviewerRelationManager;
use App\Filament\Resources\ReviewResource\Widgets\ReviewsStatsWidget;
use App\Models\Review;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use IbrahimBougaoua\FilamentRatingStar\Columns\RatingStarColumn;

class ReviewResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rating')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('comment')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('reviewer_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reviewer_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reviewer')
                    ->getStateUsing(fn($record) => $record->reviewer->user->fullName)
                    ->description(fn($record) => $record->reviewer->user->phone)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('comment')
                    ->label(__('filament/resources/review-resource.comment'))
                    ->limit(50)
                    ->sortable(),

                RatingStarColumn::make('rating')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('order.number')
                    ->label(__('filament/resources/review-resource.order-number'))
                    ->badge()
                    ->formatStateUsing(fn($state) => str_replace('ORDER-', '', $state))
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reviewer_type')
                    ->label(__('filament/resources/review-resource.reviewer-type'))
                    ->badge()
                    ->color(fn($record) => $record->reviewer->user->isCustomer ? 'warning' : 'info')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'Customer' => __('filament/resources/review-resource.customer'),
                        'Driver' => __('filament/resources/review-resource.driver'),
                    })
                    ->alignCenter()
                    ->getStateUsing(fn($record) => $record->reviewer->user->isCustomer ? 'Customer' : 'Driver')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament/resources/review-resource.posted-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // TODO: Better format the layout of the view page and add relation managers
                ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrderRelationManager::class,
            ReviewerRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'view' => Pages\ViewReview::route('/{record}'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'delete',
            'delete_any',
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ReviewsStatsWidget::class,
        ];
    }
}
