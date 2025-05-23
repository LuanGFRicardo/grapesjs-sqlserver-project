<div 
    id="code-editor-modal" 
    style="display:none; position:fixed; z-index:9999; top:50px; left:50px; right:50px;"
    class="bg-white border border-gray-300 p-4 flex flex-col">
  <textarea id="code-editor" class="w-full h-[80%] border border-gray-300 rounded p-2"></textarea>

  <div class="text-right mt-4 flex justify-end gap-2">
      <button 
        onclick="applyCodeChanges()" 
        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 border border-blue-500 rounded transition">
          Aplicar
      </button>

      <button 
        onclick="closeCodeEditor()" 
        class="bg-gray-400 hover:bg-gray-500 text-white font-medium py-2 px-4 border border-gray-500 rounded transition">
          Fechar
      </button>
  </div>
</div>