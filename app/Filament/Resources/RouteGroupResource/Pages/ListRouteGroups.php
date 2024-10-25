<?php

namespace App\Filament\Resources\RouteGroupResource\Pages;

use App\Filament\Resources\RouteGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRouteGroups extends ListRecords
{
    protected static string $resource = RouteGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
