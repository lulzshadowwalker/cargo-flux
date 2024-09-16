<?php

namespace App\Filament\Resources;

use App\Enums\SupportTicketStatus;
use App\Filament\Resources\SupportTicketResource\Pages;
use App\Filament\Resources\SupportTicketResource\Widgets\SupportTicketsStatsWidget;
use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->label(__('filament/resources/support-ticket-resource.number'))
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->formatStateUsing(fn($state) => Str::replace('TICKET-', '', $state)),

                Forms\Components\Select::make('status')
                    ->label(__('filament/resources/support-ticket-resource.status'))
                    ->required()
                    ->options(SupportTicketStatus::class)
                    ->formatStateUsing(fn($state) => SupportTicketStatus::tryFrom($state)->label()),

                Forms\Components\TextInput::make('subject')
                    ->label(__('filament/resources/support-ticket-resource.subject'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('message')
                    ->label(__('filament/resources/support-ticket-resource.message'))
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('user_id')
                    ->label(__('filament/resources/support-ticket-resource.registered-author'))
                    ->relationship('user', 'fullName')
                    ->searchable()
                    ->placeholder(__('filament/resources/support-ticket-resource.guest-author')),

                Forms\Components\TextInput::make('phone')
                    ->label(__('filament/resources/support-ticket-resource.attached-phone'))
                    ->tel()
                    ->maxLength(20),

                Forms\Components\TextInput::make('name')
                    ->label(__('filament/resources/support-ticket-resource.attached-name'))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label(__('filament/resources/support-ticket-resource.subject'))
                    ->description(fn($record) => Str::limit($record->message, 50))
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('attached_information')
                    ->label(__('filament/resources/support-ticket-resource.attached-information'))
                    ->getStateUsing(fn($record) => $record->name)
                    ->description(fn($record) => $record->phone)
                    ->searchable(
                        query: function (Builder $query, $search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        }
                    ),

                Tables\Columns\TextColumn::make('registered_author')
                    ->label(__('filament/resources/support-ticket-resource.registered-author'))
                    ->description(fn($record) => $record->user?->phone)
                    ->getStateUsing(fn($record) => $record->user?->fullName ?? __('filament/resources/support-ticket-resource.guest-author'))
                    ->badge(fn($record) => !isset($record->user))
                    ->color(fn($record) => !isset($record->user) ? Color::hex('#4b5563') : null)
                    ->sortable()
                    ->searchable(
                        query: function (Builder $query, $search) {
                            $query->whereHas('user', function (Builder $subQuery) use ($search) {
                                $subQuery->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                            });
                        }
                    ),

                Tables\Columns\TextColumn::make('user.type')
                    ->label(__('filament/resources/support-ticket-resource.author-type'))
                    ->badge()
                    ->formatStateUsing(fn($state) => $state->label())
                    ->color(fn($state) => $state->color())
                    ->sortable(),

                Tables\Columns\TextColumn::make('sent_at')
                    ->label(__('filament/resources/support-ticket-resource.sent-at'))
                    ->getStateUsing(fn($record) => $record->created_at->diffForHumans())
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament/resources/support-ticket-resource.status'))
                    ->badge()
                    ->color(fn($state) => $state->color())
                    ->formatStateUsing(fn($state) => $state->label())
                    ->sortable(),

                Tables\Columns\TextColumn::make('number')
                    ->label(__('filament/resources/support-ticket-resource.number'))
                    ->badge()
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => Str::replace('TICKET-', '', $state))
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),

                Tables\Actions\ViewAction::make()
                    ->slideOver(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('open')
                        ->label(__('filament/resources/support-ticket-resource.open'))
                        ->color(SupportTicketStatus::OPEN->color())
                        ->icon(SupportTicketStatus::OPEN->icon())
                        ->visible(fn($record) => !$record->isOpen)
                        ->action(fn($record) => $record->markAsOpen()),

                    Tables\Actions\Action::make('in-progress')
                        ->label(__('filament/resources/support-ticket-resource.in-progress'))
                        ->color(SupportTicketStatus::IN_PROGRESS->color())
                        ->icon(SupportTicketStatus::IN_PROGRESS->icon())
                        ->visible(fn($record) => !$record->isInProgress)
                        ->action(fn($record) => $record->markAsInProgress()),

                    Tables\Actions\Action::make('resolve')
                        ->label(__('filament/resources/support-ticket-resource.resolve'))
                        ->color(SupportTicketStatus::RESOLVED->color())
                        ->icon(SupportTicketStatus::RESOLVED->icon())
                        ->visible(fn($record) => !$record->isResolved)
                        ->action(fn($record) => $record->markAsResolved()),

                ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            // 'edit' => Pages\EditSupportTicket::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            SupportTicketsStatsWidget::class,
        ];
    }
}
