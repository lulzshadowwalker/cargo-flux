<?php

namespace App\Filament\Resources\TruckCategoryResource\Pages;

use App\Filament\Resources\TruckCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTruckCategory extends EditRecord
{
    protected static string $resource = TruckCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled($this->record->trucks()->exists())
                ->requiresConfirmation()
        ];
    }
}
