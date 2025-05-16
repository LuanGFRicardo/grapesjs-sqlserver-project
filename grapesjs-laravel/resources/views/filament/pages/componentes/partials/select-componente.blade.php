{{-- Campo de seleção --}}
<div class="space-y-1.5">
    <label for="selectComponente" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
        Componente
    </label>
    <select
        id="selectComponente"
        class="filament-forms-select block w-full rounded-md border-gray-300
                dark:border-gray-700 dark:bg-gray-900 dark:text-white
                shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
        <option value="">-- Selecione --</option>
        @foreach($componentes as $comp)
            <option value="{{ $comp->id }}">{{ $comp->nome }}</option>
        @endforeach
    </select>
</div>

{{-- Botões de ação --}}
<div class="pt-2">
    <button
        id="btnLoad"
        class="inline-flex items-center justify-center rounded-md 
                bg-green-600 text-white 
                px-6 py-2.5 text-sm font-semibold shadow-sm 
                hover:bg-green-700 
                focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
        Carregar
    </button>

    <button
        id="btnDelete"
        class="inline-flex items-center justify-center rounded-md 
                bg-red-600 text-white 
                px-6 py-2.5 text-sm font-semibold shadow-sm 
                hover:bg-red-700 
                focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
        Excluir
    </button>
</div>