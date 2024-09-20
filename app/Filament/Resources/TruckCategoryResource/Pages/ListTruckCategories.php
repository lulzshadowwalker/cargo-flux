<?php

namespace App\Filament\Resources\TruckCategoryResource\Pages;

use App\Filament\Resources\TruckCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTruckCategories extends ListRecords
{
    protected static string $resource = TruckCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
