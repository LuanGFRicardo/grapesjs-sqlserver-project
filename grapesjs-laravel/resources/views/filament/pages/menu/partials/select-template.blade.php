<form id="templateForm" method="GET" action="" class="space-y-4">
    <div>
        <label for="templateSelect" class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
            Template
        </label>
        <select 
            id="templateSelect"
            name="template"
            class="filament-forms-select block w-full rounded-md border-gray-300
                dark:border-gray-700 dark:bg-gray-900 dark:text-white
                shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
            <option value="">-- Escolha um template --</option>
            @foreach ($templates as $template)
                <option value="{{ $template->nome }}">{{ $template->nome }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <button 
            type="submit"
            class="inline-flex items-center justify-center rounded-md 
                bg-green-600 text-white 
                px-6 py-2.5 text-sm font-semibold shadow-sm 
                hover:bg-green-700 
                focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
            Editar
        </button>
    </div>
</form>