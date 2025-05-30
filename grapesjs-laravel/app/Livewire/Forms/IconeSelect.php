<?php

namespace App\Livewire\Forms;

use Filament\Forms\Components\Field;
use Illuminate\Support\Str;

class IconeSelect extends Field
{
    // Define a view usada pelo componente
    protected string $view = 'livewire.forms.icone-select';

    // Lista de ícones carregados do JSON
    public array $icons = [];

    // Configura o componente ao ser inicializado
    protected function setUp(): void
    {
        parent::setUp();

        // Carrega ícones do arquivo JSON
        $this->icons = $this->getIcons();

        // Atualiza estado após hidratação
        $this->afterStateHydrated(function (IconeSelect $component, $state) {
            $component->state($state);
        });
    }

    // Lê e decodifica JSON com ícones
    public function getIcons(): array
    {
        $json = file_get_contents(public_path('data/icons.json'));
        return json_decode($json, true) ?? [];
    }
}
