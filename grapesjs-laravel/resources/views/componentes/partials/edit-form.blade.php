<form id="editForm" class="mt-6 space-y-6" style="display: none;">
    <input type="hidden" id="editId">

    <div>
        <label for="editNome" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
        <input
            type="text"
            id="editNome"
            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            required>
    </div>

    <div>
        <label for="editCategoria" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
        <input
            type="text"
            id="editCategoria"
            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
        <label for="editHtml" class="block text-sm font-medium text-gray-700 mb-1">HTML Padrão</label>
        <textarea
            id="editHtml"
            rows="4"
            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            required>
        </textarea>
    </div>

    <div>
        <label for="editCss" class="block text-sm font-medium text-gray-700 mb-1">CSS</label>
        <textarea
            id="editCss"
            rows="3"
            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </textarea>
    </div>

    <button
        type="submit"
        class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-md transition">
            Salvar Alterações
    </button>
</form>