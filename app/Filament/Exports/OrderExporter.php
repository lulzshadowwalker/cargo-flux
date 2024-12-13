<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('number')
                ->label('Number'),

            ExportColumn::make('cargo')
                ->label('Cargo'),

            ExportColumn::make('amount')
                ->label('Amount'),

            ExportColumn::make('currency.code')
                ->label('Currency'),

            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn($state) => $state->label()),

            ExportColumn::make('payment_method')
                ->label('Payment Method')
                ->formatStateUsing(fn($state) => $state->label()),

            ExportColumn::make('payment_status')
                ->label('Payment Status')
                ->formatStateUsing(fn($state) => $state->label()),

            ExportColumn::make('scheduled_at')
                ->label('Scheduled At'),

            ExportColumn::make('pickup_location')
                ->label('Pickup Location')
                ->state(fn ($record) => $record->pickup_location_latitude . ', ' . $record->pickup_location_longitude),

            ExportColumn::make('delivery_location')
                ->label('Delivery Location')
                ->state(fn ($record) => $record->delivery_location_latitude . ', ' . $record->delivery_location_longitude),

            ExportColumn::make('current_location')
                ->label('Current Location')
                ->state(fn ($record) => $record->current_location_latitude . ', ' . $record->current_location_longitude . ' at ' . $record->current_location_recorded_at),

            ExportColumn::make('customer.fullName')
                ->label('Customer Name'),

            ExportColumn::make('customer.phone')
                ->label('Customer Phone'),

            ExportColumn::make('driver.fullName')
                ->label('Driver Name'),

            ExportColumn::make('driver.phone')
                ->label('Driver Phone'),

            ExportColumn::make('truckCategory.name')
                ->label('Truck Category'),

            ExportColumn::make('truck.license_plate')
                ->label('Truck License Plate'),

            ExportColumn::make('created_at')
                ->label('Date Created'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        //  TODO: Translations
        $body = 'Your order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
