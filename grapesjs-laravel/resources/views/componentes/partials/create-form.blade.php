<form id="createForm" class="space-y-6">
    <div>
        <label for="novoNome" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
        <input
            type="text"
            id="novoNome"
            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="ex: Componente SQL"
            required>
    </div>

    <div>
        <label for="novoCategoria" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
        <input
            type="text"
            id="novoCategoria"
            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="ex: Formulários">
    </div>

    <div>
        <label for="novoHtml" class="block text-sm font-medium text-gray-700 mb-1">HTML Padrão</label>
        <textarea
            id="novoHtml"
            rows="4"
            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </textarea>
    </div>

    <div>
        <label for="novoCss" class="block text-sm font-medium text-gray-700 mb-1">CSS</label>
        <textarea
            id="novoCss"
            rows="3"
            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </textarea>
    </div>

    <div class="flex flex-wrap gap-4">
        <button
            type="submit"
            class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-md transition">
                Criar Componentes
        </button>
        <button
            type="button"
            onclick="voltarParaMenu()"
            class="bg-white hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 border border-gray-300 rounded transition">
                ⬅️ Voltar ao Menu
        </button>
    </div>
</form>
