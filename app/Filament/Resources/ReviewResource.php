<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ReviewExporter;
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
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use IbrahimBougaoua\FilamentRatingStar\Columns\Components\RatingStar as RatingStarColumn;
use IbrahimBougaoua\FilamentRatingStar\Forms\Components\RatingStar;

class ReviewResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament/resources/review-resource.review'))
                    ->aside()
                    ->schema([
                        RatingStar::make('rating')
                            ->label(__('filament/resources/review-resource.rating')),

                        Forms\Components\Textarea::make('comment')
                            ->label(__('filament/resources/review-resource.comment'))
                            ->columnSpanFull()
                            ->rows(8),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reviewer')
                    ->label(__('filament/resources/review-resource.reviewer'))
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
            ->headerActions([
             ExportAction::make()
                ->exporter(ReviewExporter::class)
            ])
            ->actions([
                // TODO: Better format the layout of the view page and add relation managers
                ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

                ExportBulkAction::make()
                    ->exporter(ReviewExporter::class),
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
