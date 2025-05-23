<?php

namespace App\Filament\Resources\ComponentesResource\Pages;

use App\Filament\Resources\ComponentesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComponentes extends EditRecord
{
    protected static string $resource = ComponentesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl();
    }

    protected function getScripts(): array
    {
        return [
            asset('js/custom-select.blade.php'),
        ];
    }
}
