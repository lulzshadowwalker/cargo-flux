<?php

namespace App\Filament\Exports;

use App\Models\SupportTicket;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SupportTicketExporter extends Exporter
{
    protected static ?string $model = SupportTicket::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('number')
                ->label('Number'),

            ExportColumn::make('message')
                ->label('Message'),

            ExportColumn::make('phone')
                ->label('Attached Phone'),

            ExportColumn::make('name')
                ->label('Attached Name'),

            ExportColumn::make('registered_author')
                ->label('Author Name')
                ->state(fn($record) => $record->user?->fullName ?? '[Guest Author]'),

            ExportColumn::make('user.type')
                ->label('Author Type')
                ->formatStateUsing(fn($state) => $state?->label() ?? '[Guest Author]'),

            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn($state) => $state->label()),

            ExportColumn::make('created_at')
                ->label('Date Submitted'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        //  TODO: Translations
        $body = 'Your support ticket export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
