<?php

namespace Tests\Traits;

use Filament\Facades\Filament;
use Outerweb\FilamentTranslatableFields\Filament\Plugins\FilamentTranslatableFieldsPlugin;

trait WithFilamentTranslatableFieldsPlugin
{
    public function setUpWithFilamentTranslatableFieldsPlugin(): void
    {
        FilamentTranslatableFieldsPlugin::make()->boot(Filament::getCurrentPanel());
    }
}
