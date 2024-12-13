<?php

namespace App\Filament\Exports;

use App\Models\Truck;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TruckExporter extends Exporter
{
    protected static ?string $model = Truck::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('driver.fullName')
                ->label('Driver Name'),

            ExportColumn::make('driver.phone')
                ->label('Driver Phone'),

            ExportColumn::make('license_plate')
                ->label('License Plate'),

            ExportColumn::make('category.name')
                ->label('Category'),

            ExportColumn::make('category.tonnage')
                ->label('Tonnage'),

            ExportColumn::make('order_count')
                ->label('Orders')
                ->state(fn ($record) => $record->orders()->count()),

            ExportColumn::make('created_at')
                ->label('Date Created'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        //  TODO: Translations
        $body = 'Your truck export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
