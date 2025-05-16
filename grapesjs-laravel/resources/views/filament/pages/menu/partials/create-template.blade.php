<form id="createTemplateForm" class="space-y-4">
    {{-- Campo: Nome do Template --}}
    <div>
        <label for="novoTemplateNome" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">
            Nome do Template
        </label>
        <input 
            type="text" 
            id="novoTemplateNome" 
            name="novoTemplateNome"
            class="filament-forms-input block w-full py-2 px-4 border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:text-sm" 
            placeholder="Nome do novo template" 
            required>
    </div>

    {{-- Botão Criar Template --}}
    <div>
        <button 
            type="submit" 
            class="inline-flex items-center justify-center rounded-md 
                bg-green-600 text-white 
                px-6 py-2.5 text-sm font-semibold shadow-sm 
                hover:bg-green-700 
                focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
            Criar Template
        </button>
    </div>
</form>