<form id="editForm" class="mt-6 space-y-6" style="display: none;">
    <input type="hidden" id="editId">

    {{-- Nome --}}
    <div class="space-y-1.5">
        <label for="editNome" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            Nome
        </label>
        <input
            type="text"
            id="editNome"
            required
            class="filament-forms-input block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
    </div>

    {{-- Categoria --}}
    <div class="space-y-1.5">
        <label for="editCategoria" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            Categoria
        </label>
        <input
            type="text"
            id="editCategoria"
            class="filament-forms-input block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" />
    </div>

    {{-- Campo: Ícones --}}
    <div class="space-y-1.5">
        <label for="editIcone" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            Ícone
        </label>
        <select
            id="editIcone"
            name="editIcone"
            class="filament-forms-select block w-full rounded-md border-gray-300
                dark:border-gray-700 dark:bg-gray-900 dark:text-white
                shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
            <option value="">Selecione um ícone...</option>
        </select>
        @error('editIcone')
            <p class="text-xs text-danger-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- HTML Padrão --}}
    <div class="space-y-1.5">
        <label for="editHtml" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            HTML Padrão
        </label>
        <textarea
            id="editHtml"
            rows="4"
            required
            class="filament-forms-textarea block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
    </div>

    {{-- CSS --}}
    <div class="space-y-1.5">
        <label for="editCss" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            CSS
        </label>
        <textarea
            id="editCss"
            rows="3"
            class="filament-forms-textarea block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
    </div>

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