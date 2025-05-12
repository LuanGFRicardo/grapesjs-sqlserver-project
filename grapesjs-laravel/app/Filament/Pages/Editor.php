<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\MenuService;
use Illuminate\Support\Facades\Log;

class Editor extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.menu.index';

    public $templates;

    protected MenuService $menuService;

    public function mount(MenuService $menuService): void
    {
        $this->menuService = $menuService;

        try {
            $this->templates = $this->menuService->listarTemplates();
        } catch (\Exception $e) {
            Log::error('Erro ao carregar templates no editor: ' . $e->getMessage());
            $this->templates = collect();
        }
    }

    protected function getViewData(): array
    {
        return [
            'templates' => $this->templates,
        ];
    }
}
