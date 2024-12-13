<?php

namespace App\Filament\Exports;

use App\Models\Review;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ReviewExporter extends Exporter
{
    protected static ?string $model = Review::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('rating')
                ->label('Rating'),

            ExportColumn::make('comment')
                ->label('Comment'),

            ExportColumn::make('reviewer_name')
                ->label('Reviewer Name')
                ->state(fn($record) => $record->reviewer->user->fullName),

            ExportColumn::make('reviewer_phone')
                ->label('Reviewer Phone')
                ->state(fn($record) => $record->reviewer->user->phone),

            ExportColumn::make('order.number')
                ->label('Order Number'),

            ExportColumn::make('created_at')
                ->label('Date Created'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        //  TODO: Translations
        $body = 'Your review export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
