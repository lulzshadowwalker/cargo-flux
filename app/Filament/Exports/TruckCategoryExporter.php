<?php

namespace App\Filament\Exports;

use App\Models\TruckCategory;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TruckCategoryExporter extends Exporter
{
    protected static ?string $model = TruckCategory::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('name')
                ->label('Name'),

            ExportColumn::make('tonnage')
                ->label('Tonnage'),

            ExportColumn::make('length')
                ->label('Length'),

            ExportColumn::make('truck_count')
                ->label('Trucks')
                ->state(fn($record) => $record->trucks()->count()),

            ExportColumn::make('order_count')
                ->label('Orders')
                ->state(fn($record) => $record->orders()->count()),

            ExportColumn::make('availability')
                ->label('Availability')
                ->formatStateUsing(fn($state) => $state ? 'Availabile' : 'Not Available')
                ->state(fn($record) => $record->isAvailable),

            ExportColumn::make('created_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your truck category export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
