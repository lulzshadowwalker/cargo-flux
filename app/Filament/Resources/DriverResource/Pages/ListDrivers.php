<?php

namespace App\Filament\Resources\DriverResource\Pages;

use App\Enums\DriverStatus;
use App\Filament\Resources\DriverResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListDrivers extends ListRecords
{
    protected static string $resource = DriverResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('filament/resources/driver-resource.all-drivers')),
            'under-review' => Tab::make(__('filament/resources/driver-resource.under-review'))
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', DriverStatus::UNDER_REVIEW);
                }),
            'approved' => Tab::make(__('filament/resources/driver-resource.approved'))
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', DriverStatus::APPROVED);
                }),
            'rejected' => Tab::make(__('filament/resources/driver-resource.rejected'))
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', DriverStatus::REJECTED);
                }),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
