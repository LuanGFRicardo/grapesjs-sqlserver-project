<div class="flex flex-col md:flex-row md:items-end md:gap-6 space-y-4 md:space-y-0">
    <div class="w-full md:w-1/3">
        <label for="selectComponente" class="block text-sm font-medium text-gray-700 mb-1">Componente</label>
        <select
            id="selectComponente"
            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Selecione --</option>
            @foreach($componentes as $comp)
                <option value="{{ $comp->id }}">{{ $comp->nome }}</option>
            @endforeach
        </select>
    </div>

    <div class="w-full md:w-2/3 flex justify-end gap-4">
        <button
            id="btnLoad"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-md transition">
                Carregar
        </button>
        <button
            id="btnDelete"
            class="border border-red-500 text-red-500 hover:bg-red-100 font-medium py-2 px-6 rounded-md transition">
                Excluir
        </button>
    </div>
</div>