<?php

namespace App\Filament\Resources\ComponenteResource\Pages;

use App\Filament\Resources\ComponenteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateComponente extends CreateRecord
{
    protected static string $resource = ComponenteResource::class;

    // Redireciona para a listagem após criação
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl();
    }

    // Define ações do formulário (criar e cancelar)
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    // Ajusta dados do formulário antes de criar
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['icone'] = $this->form->getState()['icone'];
        return $data;
    }
}
