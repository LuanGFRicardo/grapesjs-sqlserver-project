<?php

namespace App\Livewire\Forms;

use Filament\Forms\Components\Field;
use Illuminate\Support\Str;

class IconeSelect extends Field
{
    protected string $view = 'livewire.forms.icone-select';

    public array $icons = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->icons = $this->getIcons();

        $this->afterStateHydrated(function (IconeSelect $component, $state) {
            $component->state($state);
        });
    }

    public function getIcons(): array
    {
        $json = file_get_contents(public_path('data/icons.json'));
        return json_decode($json, true) ?? [];
    }
}
