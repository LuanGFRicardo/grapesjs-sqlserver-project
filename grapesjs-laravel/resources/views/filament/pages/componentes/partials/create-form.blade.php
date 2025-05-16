<form id="createForm" wire:submit.prevent="createForm" class="space-y-6">

    {{-- Campo: Nome --}}
    <div class="space-y-1.5">
        <label for="novoNome" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            Nome
        </label>
        <input
            type="text"
            id="novoNome"
            wire:model.defer="novoNome"
            placeholder="ex: Componente SQL"
            required
            class="filament-forms-input block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
        @error('novoNome')
            <p class="text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Campo: Categoria --}}
    <div class="space-y-1.5">
        <label for="novoCategoria" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            Categoria
        </label>
        <input
            type="text"
            id="novoCategoria"
            wire:model.defer="novoCategoria"
            placeholder="ex: Formulários"
            class="filament-forms-input block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
        @error('novoCategoria')
            <p class="text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Campo: Ícones --}}
    <div class="space-y-1.5">
        <label for="novoIcone" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            Ícone
        </label>
        <select id="novoIcone" name="novoIcone" class="filament-forms-select block w-full">
            <option value="">Selecione um ícone...</option>
        </select>
        @error('novoIcone')
            <p class="text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Campo: HTML --}}
    <div class="space-y-1.5">
        <label for="novoHtml" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            HTML Padrão
        </label>
        <textarea
            id="novoHtml"
            rows="4"
            wire:model.defer="novoHtml"
            class="filament-forms-textarea block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
        @error('novoHtml')
            <p class="text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Campo: CSS --}}
    <div class="space-y-1.5">
        <label for="novoCss" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            CSS
        </label>
        <textarea
            id="novoCss"
            rows="3"
            wire:model.defer="novoCss"
            class="filament-forms-textarea block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
        @error('novoCss')
            <p class="text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Botão --}}
    <div class="pt-2">
        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-md 
                bg-green-600 text-white 
                px-6 py-2.5 text-sm font-semibold shadow-sm 
                hover:bg-green-700 
                focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
            Salvar Alterações
        </button>
    </div>
</form>
