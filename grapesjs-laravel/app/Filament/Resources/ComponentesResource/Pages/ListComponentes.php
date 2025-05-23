<?php

namespace App\Filament\Resources\ComponentesResource\Pages;

use App\Filament\Resources\ComponentesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComponentes extends ListRecords
{
    protected static string $resource = ComponentesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
