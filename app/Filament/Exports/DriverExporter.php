<?php

namespace App\Filament\Exports;

use App\Enums\OrderStatus;
use App\Models\Driver;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class DriverExporter extends Exporter
{
    protected static ?string $model = Driver::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('user.fullName')
                ->label('Name'),

            ExportColumn::make('user.phone')
                ->label('Phone'),

            ExportColumn::make('user.email')
                ->label('Email'),

            ExportColumn::make('upcoming_orders')
                ->label('Upcoming Orders')
                ->state(function ($record) {
                    if ($activeOrder = $record->orders()->active()->first()) {
                        return $activeOrder->status->label();
                    }

                    if ($record->orders()->scheduled()->count() > 0) {
                        return OrderStatus::SCHEDULED->label();
                    }

                    return 'none';
                }),

            ExportColumn::make('order_count')
                ->label('Orders')
                ->state(fn($record) => $record->orders()->count()),

            ExportColumn::make('user.status')
                ->label('Status')
                ->formatStateUsing(fn($state) => $state->label()),

            ExportColumn::make('user.status')
                ->label('Status')
                ->formatStateUsing(fn($state) => $state->label()),

            ExportColumn::make('status')
                ->label('Registeration Status')
                ->formatStateUsing(fn($state) => $state->label()),

            ExportColumn::make('created_at'),

            ExportColumn::make('iban')
                ->label('IBAN'),

            ExportColumn::make('created_at')
                ->label('Date Joined'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your driver export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
